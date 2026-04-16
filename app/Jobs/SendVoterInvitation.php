<?php

namespace App\Jobs;

use App\Mail\VoterInvitationMail;
use App\Models\VoterInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120];

    public function __construct(private VoterInvitation $invitation) {}

    public function handle(): void
    {
        // Skip if already sent
        if ($this->invitation->email_status === 'sent') {
            return;
        }

        try {
            $user = $this->invitation->user;
            $election = $this->invitation->election;
            $organisation = $election->organisation;

            // Get language with fallback
            $lang = is_array($organisation->languages) && !empty($organisation->languages)
                ? $organisation->languages[0]
                : 'de';

            $content = $this->getLocalizedContent($lang, [
                'fullname' => $user->name,
                'election_name' => $election->name,
                'organisation_name' => $organisation->name,
            ]);

            $resetUrl = url("/invitation/{$this->invitation->token}");

            Mail::to($user->email)
                ->locale($lang)
                ->send(new VoterInvitationMail(
                    content: $content,
                    resetUrl: $resetUrl,
                    lang: $lang
                ));

            $this->invitation->update([
                'email_status' => 'sent',
                'sent_at' => now(),
                'email_error' => null,
            ]);

        } catch (\Exception $e) {
            $this->invitation->update([
                'email_status' => 'failed',
                'email_error' => $e->getMessage(),
            ]);

            throw $e; // Retry
        }
    }

    private function getLocalizedContent(string $lang, array $data): array
    {
        return match ($lang) {
            'en' => [
                'subject' => 'You have been invited to vote: ' . $data['election_name'],
                'greeting' => 'Hello ' . $data['fullname'] . ',',
                'body' => 'You have been registered as a voter for the following election:',
                'election_label' => 'Election:',
                'organisation_label' => 'Organisation:',
                'button_text' => 'Set Password & Vote',
                'expiry_note' => 'This link is valid for 7 days.',
                'ignore_note' => 'If you did not expect this invitation, please ignore this email.',
            ],
            'np' => [
                'subject' => 'तपाईंलाई मतदानको लागि आमन्त्रित गरिएको छ: ' . $data['election_name'],
                'greeting' => 'नमस्ते ' . $data['fullname'] . ',',
                'body' => 'तपाईंलाई निम्न निर्वाचनको लागि मतदाताको रूपमा दर्ता गरिएको छ:',
                'election_label' => 'निर्वाचन:',
                'organisation_label' => 'संगठन:',
                'button_text' => 'पासवर्ड सेट गर्नुहोस् र मतदान गर्नुहोस्',
                'expiry_note' => 'यो लिङ्क ७ दिनको लागि मान्य छ।',
                'ignore_note' => 'यदि तपाईंले यो आमन्त्रणको अपेक्षा गर्नुभएको थिएन भने, कृपया यो इमेललाई बेवास्ता गर्नुहोस्।',
            ],
            default => [
                'subject' => 'Sie wurden zur Wahl eingeladen: ' . $data['election_name'],
                'greeting' => 'Hallo ' . $data['fullname'] . ',',
                'body' => 'Sie wurden als Wähler für die folgende Wahl registriert:',
                'election_label' => 'Wahl:',
                'organisation_label' => 'Organisation:',
                'button_text' => 'Passwort festlegen & Abstimmen',
                'expiry_note' => 'Dieser Link ist 7 Tage gültig.',
                'ignore_note' => 'Falls Sie diese Einladung nicht erwartet haben, ignorieren Sie bitte diese E-Mail.',
            ],
        };
    }
}
