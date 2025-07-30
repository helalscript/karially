<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f8; padding: 40px 0;">
    <div
        style="max-width: 650px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background-color: #00466a; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; color: #ffffff;">New Coach Match Request </h1>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">Hello <strong></strong>,
            </p>

            <h2 style="font-size: 20px; color: #00466a; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">New
                Coach Match Request Details</h2>

            <table style="width: 100%; margin-top: 20px; font-size: 15px; color: #333333;">
                <tr>
                    <td style="padding: 8px 0;"><strong>Name:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Email:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['email'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Navigating Career:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['navigating_career'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Session Help:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['session_help'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Current Role:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['current_role'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Career Goal:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['career_goal'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Preferred Time:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['preferred_time'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; vertical-align: top;"><strong>Additional Info:</strong></td>
                    <td style="padding: 8px 0;">{{ $data['additional_info'] ?? 'N/A' }}</td>
                </tr>
            </table>

            <p style="font-size: 14px; color: #666666; margin-top: 30px;">Please review the user's responses and find a coach that aligns with their goals. Ensure the user is notified within 24 hours with the coach and scheduling link.</p>

            <p style="font-size: 14px; color: #333333; margin-top: 20px;">Best
                regards,<br><strong>{{ config('app.name') }}</strong></p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f0f3f5; text-align: center; padding: 15px; font-size: 12px; color: #888888;">
            <p style="margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }} Inc. All rights reserved.</p>
        </div>
    </div>
</div>
