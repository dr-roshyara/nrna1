#!/bin/bash

# Clear the screen (similar to @echo off and title)
clear
echo -e "\033]0;DeepSeek Claude - Election Project\007"  # Set terminal title

echo "========================================"
echo "   🤖 Claude Code with DeepSeek"
echo "========================================"
echo

export ANTHROPIC_BASE_URL=https://api.deepseek.com/anthropic
export ANTHROPIC_AUTH_TOKEN=sk-de555ad4e5b84f33a1703ca06a377804
export ANTHROPIC_MODEL=deepseek-chat
export ANTHROPIC_SMALL_FAST_MODEL=deepseek-chat
export API_TIMEOUT_MS=600000

echo "Starting Claude Code with DeepSeek backend..."
echo "Project: election"
echo
claude

echo
echo "Session completed."
sleep 3
