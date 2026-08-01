<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Anonymity & Privacy | PublicDigit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }
        
        .header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .header .subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .privacy-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 2rem;
            color: #4a5568;
            margin-bottom: 1.5rem;
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.5rem;
            display: inline-block;
        }
        
        .architecture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .arch-card {
            background: #f7fafc;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .arch-card:hover {
            transform: translateY(-5px);
        }
        
        .arch-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .arch-card h3 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .arch-card p {
            color: #4a5568;
            font-size: 0.95rem;
        }
        
        .data-flow {
            background: #ebf4ff;
            border-radius: 15px;
            padding: 2rem;
            margin: 3rem 0;
            border: 2px dashed #667eea;
        }
        
        .data-flow h3 {
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        
        .flow-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .step {
            flex: 1;
            min-width: 150px;
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .step .step-number {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: bold;
        }
        
        .step .arrow {
            font-size: 2rem;
            color: #667eea;
        }
        
        .verification-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .verification-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 2rem;
        }
        
        .verification-card.receipt {
            border-color: #48bb78;
        }
        
        .verification-card.admin {
            border-color: #f6ad55;
        }
        
        .badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .badge.receipt {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .badge.admin {
            background: #feebc8;
            color: #744210;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin: 3rem 0;
            text-align: center;
        }
        
        .stat {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        
        .stat .number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat .label {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .faq-section {
            margin: 3rem 0;
        }
        
        .faq-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 0;
        }
        
        .faq-question {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .faq-answer {
            color: #4a5568;
            padding-left: 1.5rem;
            border-left: 3px solid #667eea;
        }
        
        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 3rem 0;
            flex-wrap: wrap;
        }
        
        .trust-badge {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 10px;
            min-width: 150px;
        }
        
        .trust-badge .emoji {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .footer {
            text-align: center;
            color: white;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .privacy-card {
                padding: 1.5rem;
            }
            
            .verification-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .flow-steps {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Your Vote, Your Privacy</h1>
            <p class="subtitle">How PublicDigit protects your identity while ensuring election integrity</p>
        </div>
        
        <!-- Main Content Card -->
        <div class="privacy-card">
            <!-- Introduction -->
            <h2 class="section-title">The Promise of Anonymous Voting</h2>
            <p style="font-size: 1.1rem; margin-bottom: 2rem;">
                When you vote on PublicDigit, we make you a promise: <strong>we will never know how you voted.</strong> 
                Not us, not election officials, not anyone. Your vote is anonymous, private, and secure.
            </p>
            
            <!-- Architecture Visualization -->
            <h2 class="section-title" style="margin-top: 2rem;">🏗️ How It Works</h2>
            
            <!-- Architecture Diagram (SVG) -->
            <div style="background: #f8fafc; border-radius: 15px; padding: 2rem; margin: 2rem 0;">
                <svg viewBox="0 0 800 300" style="width: 100%; height: auto;">
                    <!-- User -->
                    <rect x="50" y="120" width="100" height="60" rx="10" fill="#667eea" opacity="0.2" stroke="#667eea" stroke-width="2"/>
                    <text x="100" y="155" text-anchor="middle" fill="#2d3748" font-weight="bold">🗳️ Voter</text>
                    
                    <!-- Device Info -->
                    <rect x="200" y="80" width="120" height="80" rx="10" fill="#9f7aea" opacity="0.2" stroke="#9f7aea" stroke-width="2"/>
                    <text x="260" y="120" text-anchor="middle" fill="#2d3748" font-size="12">📱 Device</text>
                    <text x="260" y="140" text-anchor="middle" fill="#4a5568" font-size="10">IP • Browser • Screen</text>
                    
                    <!-- Code -->
                    <rect x="370" y="80" width="120" height="80" rx="10" fill="#48bb78" opacity="0.2" stroke="#48bb78" stroke-width="2"/>
                    <text x="430" y="120" text-anchor="middle" fill="#2d3748" font-size="12">🔑 Voting Code</text>
                    <text x="430" y="140" text-anchor="middle" fill="#4a5568" font-size="10">Holds device fingerprint</text>
                    
                    <!-- Vote -->
                    <rect x="540" y="120" width="100" height="60" rx="10" fill="#f6ad55" opacity="0.2" stroke="#f6ad55" stroke-width="2"/>
                    <text x="590" y="155" text-anchor="middle" fill="#2d3748" font-weight="bold">🗳️ Vote</text>
                    
                    <!-- Arrows -->
                    <defs>
                        <marker id="arrowhead" markerWidth="10" markerHeight="10" refX="9" refY="5" orient="auto">
                            <polygon points="0 0, 10 5, 0 10" fill="#667eea"/>
                        </marker>
                    </defs>
                    
                    <line x1="150" y1="150" x2="200" y2="120" stroke="#667eea" stroke-width="2" marker-end="url(#arrowhead)"/>
                    <line x1="320" y1="120" x2="370" y2="120" stroke="#667eea" stroke-width="2" marker-end="url(#arrowhead)"/>
                    <line x1="490" y1="120" x2="540" y2="150" stroke="#667eea" stroke-width="2" marker-end="url(#arrowhead)"/>
                    
                    <!-- X on vote -->
                    <line x1="630" y1="100" x2="670" y2="140" stroke="#e53e3e" stroke-width="3" stroke-dasharray="5,5"/>
                    <text x="650" y="80" text-anchor="middle" fill="#e53e3e" font-size="12">NO user ID</text>
                </svg>
            </div>
            
            <!-- Three Pillars -->
            <div class="architecture-grid">
                <div class="arch-card">
                    <div class="icon">🔒</div>
                    <h3>Complete Anonymity</h3>
                    <p>Your vote is stored with NO link to your identity. We literally cannot know how you voted - by design.</p>
                </div>
                
                <div class="arch-card">
                    <div class="icon">📱</div>
                    <h3>Device Fingerprinting</h3>
                    <p>We store a secure, one-way hash of your device info - enough to detect fraud, impossible to identify you.</p>
                </div>
                
                <div class="arch-card">
                    <div class="icon">🔑</div>
                    <h3>Receipt Verification</h3>
                    <p>After voting, you get a receipt to verify your vote was counted - without revealing how you voted.</p>
                </div>
            </div>
            
            <!-- Data Flow Explanation -->
            <div class="data-flow">
                <h3>📊 What We Store vs. What We DON'T Store</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
                    <div style="background: white; padding: 1.5rem; border-radius: 10px;">
                        <h4 style="color: #48bb78; margin-bottom: 1rem;">✅ We Store (Anonymized)</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.8rem;">• 🔒 <strong>Hashed device fingerprint</strong> (one-way, can't be reversed)</li>
                            <li style="margin-bottom: 0.8rem;">• 🌍 <strong>Country code only</strong> (from IP, not exact location)</li>
                            <li style="margin-bottom: 0.8rem;">• 📱 <strong>Browser family</strong> (Chrome/Firefox/Safari - not version)</li>
                            <li style="margin-bottom: 0.8rem;">• 📱 <strong>Device type</strong> (mobile/desktop/tablet)</li>
                            <li style="margin-bottom: 0.8rem;">• 🔑 <strong>Receipt hash</strong> (for your verification)</li>
                        </ul>
                    </div>
                    
                    <div style="background: white; padding: 1.5rem; border-radius: 10px;">
                        <h4 style="color: #e53e3e; margin-bottom: 1rem;">❌ We NEVER Store</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>Your name</strong></li>
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>Your user ID</strong> (votes have NO user link)</li>
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>Raw IP address</strong> (only hashed)</li>
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>Raw browser details</strong> (only family)</li>
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>Exact location</strong> (only country)</li>
                            <li style="margin-bottom: 0.8rem;">• ❌ <strong>MAC address</strong> (never collected)</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Verification Methods -->
            <h2 class="section-title" style="margin-top: 2rem;">🔍 Two Ways to Verify</h2>
            
            <div class="verification-grid">
                <div class="verification-card receipt">
                    <div class="badge receipt">🔐 FOR VOTERS</div>
                    <h3 style="color: #22543d; margin-bottom: 1rem;">Receipt Verification</h3>
                    <p style="margin-bottom: 1.5rem;">After voting, you receive a unique receipt string. Use it to verify your vote was counted - without revealing who you voted for.</p>
                    <div style="background: #f0fff4; padding: 1rem; border-radius: 8px;">
                        <code style="color: #22543d;">publicdigit.com/verify?vote=XYZ123...</code>
                    </div>
                </div>
                
                <div class="verification-card admin">
                    <div class="badge admin">⚖️ FOR AUDITORS</div>
                    <h3 style="color: #744210; margin-bottom: 1rem;">Participation Proof</h3>
                    <p style="margin-bottom: 1.5rem;">If someone claims "I didn't vote," auditors can prove participation using IP + identity hash - without revealing the vote choice.</p>
                    <div style="background: #fffaf0; padding: 1rem; border-radius: 8px;">
                        <code style="color: #744210;">Participation: ✓ Verified (vote remains secret)</code>
                    </div>
                </div>
            </div>
            
            <!-- Device Fingerprinting Deep Dive -->
            <h2 class="section-title" style="margin-top: 2rem;">📱 About Device Fingerprinting</h2>
            
            <div style="background: #f7fafc; border-radius: 15px; padding: 2rem; margin: 2rem 0;">
                <h3 style="color: #2d3748; margin-bottom: 1rem;">Why we do it:</h3>
                <p style="margin-bottom: 1.5rem;">To prevent voting fraud while protecting your privacy. Device fingerprinting helps us detect:</p>
                
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 1rem; padding-left: 2rem; position: relative;">
                        <span style="position: absolute; left: 0;">🛡️</span>
                        <strong>Multiple votes from the same device</strong> - Stops ballot stuffing
                    </li>
                    <li style="margin-bottom: 1rem; padding-left: 2rem; position: relative;">
                        <span style="position: absolute; left: 0;">🤖</span>
                        <strong>Automated voting bots</strong> - Ensures real human voters
                    </li>
                    <li style="margin-bottom: 1rem; padding-left: 2rem; position: relative;">
                        <span style="position: absolute; left: 0;">🔍</span>
                        <strong>Suspicious patterns</strong> - Protects election integrity
                    </li>
                </ul>
                
                <h3 style="color: #2d3748; margin: 2rem 0 1rem;">How we protect you:</h3>
                
                <div class="flow-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <p>We collect device info</p>
                        <small style="color: #718096;">IP, browser, screen size</small>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <p>We add a secret salt</p>
                        <small style="color: #718096;">Unique to PublicDigit</small>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <p>SHA256 hash</p>
                        <small style="color: #718096;">One-way, irreversible</small>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <p>Store only the hash</p>
                        <small style="color: #718096;">Raw data discarded</small>
                    </div>
                </div>
                
                <p style="margin-top: 2rem; font-style: italic; color: #4a5568;">
                    🔒 The hash is mathematically impossible to reverse - we can never recover your actual device information.
                </p>
            </div>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat">
                    <div class="number">100%</div>
                    <div class="label">Anonymous Votes</div>
                </div>
                <div class="stat">
                    <div class="number">🔐</div>
                    <div class="label">Zero User ID in Votes</div>
                </div>
                <div class="stat">
                    <div class="number">🛡️</div>
                    <div class="label">Fraud Detection Active</div>
                </div>
            </div>
            
            <!-- FAQ -->
            <h2 class="section-title" style="margin-top: 2rem;">❓ Frequently Asked Questions</h2>
            
            <div class="faq-section">
                <div class="faq-item">
                    <div class="faq-question">Q: Can PublicDigit see how I voted?</div>
                    <div class="faq-answer">A: No. By design, we store NO link between your identity and your vote. Your vote is completely anonymous - even we cannot see it.</div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Q: What device information do you collect?</div>
                    <div class="faq-answer">A: We collect basic browser/device information (IP, user agent, screen resolution) but we immediately hash it with a secret salt. Only the irreversible hash is stored - the original data is discarded. This lets us detect fraud without identifying you.</div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Q: Can I verify my vote was counted?</div>
                    <div class="faq-answer">A: Yes! After voting, you receive a unique receipt string. Visit publicdigit.com/verify and enter it to confirm your vote was counted - without revealing how you voted.</div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Q: What if someone claims I didn't vote?</div>
                    <div class="faq-answer">A: Election officials can use your IP + identity to generate a participation proof. This proves you voted without revealing your choice. It's like proving you were at the polling station without showing your ballot.</div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Q: Can my device fingerprint be traced back to me?</div>
                    <div class="faq-answer">A: No. We use a one-way cryptographic hash with a secret salt. This is mathematically irreversible - it's like trying to un-bake a cake to find the recipe. Your privacy is absolute.</div>
                </div>
            </div>
            
            <!-- Trust Badges -->
            <div class="trust-badges">
                <div class="trust-badge">
                    <div class="emoji">🔒</div>
                    <div>GDPR Compliant</div>
                </div>
                <div class="trust-badge">
                    <div class="emoji">🛡️</div>
                    <div>Privacy by Design</div>
                </div>
                <div class="trust-badge">
                    <div class="emoji">🔐</div>
                    <div>End-to-End Encryption</div>
                </div>
                <div class="trust-badge">
                    <div class="emoji">🇪🇺</div>
                    <div>EU Data Protection</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>© 2026 PublicDigit - Your Vote, Your Privacy, Your Voice</p>
            <p style="margin-top: 1rem; font-size: 0.9rem;">
                <a href="/privacy" style="color: white; text-decoration: underline;">Full Privacy Policy</a> • 
                <a href="/security" style="color: white; text-decoration: underline;">Security Overview</a> • 
                <a href="/contact" style="color: white; text-decoration: underline;">Contact DPO</a>
            </p>
        </div>
    </div>
</body>
</html>