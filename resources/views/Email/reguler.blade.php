{{-- Legacy implementation — retained for reference; inactive.
<div>
    <img width='100%' src="{{ '/banner/exhibitions/' . $data->banner_file }}">
    <p>Dear <b>{{ Str::Upper($data->name) }}</b></p>
    <p>Thank you for pre-registering to visit {{ $data->full_name }} - Your Online Visitor Pre-registration is
        Confirmed.
        We are pleased to confirm that your registration is
        successful.</p>

    <p><b><u>Collecting Badge</u></b><br /><b>Step 1: Print / save</b> this confirmation email and bring it on the day
        of your visit. Ensure that the QR Code is printed clearly.<br /><b>Step 2: </b> Scan the barcode at Visitor
        <b>Self-Printing Counter</b> and collect your badge.
    </p>
    <br>
    <img style="width: 150px;height: 150px;" src="{{ $url }}">
    <br>
    <table style='width: 75%;'>
        <tbody>
            <tr>
                <td><b><u>Your Information</u></b></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Company Name</td>
                <td>:</td>
                <td>{{ $data->company }}
                </td>
            </tr>
            <tr>
                <td>Visitor Name</td>
                <td>:</td>
                <td>{{ $data->name }}
                </td>
            </tr>
            <tr>
                <td>Title</td>
                <td>:</td>
                <td>{{ $data->job_title }}
                </td>
            </tr>

--}}
{{-- <tr>
                <td>Address</td>
                <td>:</td>
                <td>{{ $req->address }}
                </td>
            </tr> --}}
{{-- Legacy implementation — retained for reference; inactive.

            <tr>
                <td>City</td>
                <td>:</td>
                <td>{{ $data->city }}
                </td>
            </tr>
            <tr>
                <td>Country</td>
                <td>:</td>
                <td>{{ $data->country }}
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $data->email }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: baseline">Opening Hours</td>
                <td style="vertical-align: baseline">:</td>
                <td>{!! $data->opening_hours !!}
                </td>
            </tr>
        </tbody>
    </table><br />
    <p>We look forward to welcoming you at the event.<br />
        <br />Yours sincerely<br /><br />
    <p>{{ $data->team }}</p>
</div>
--}}

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>Your Online Visitor Pre-registration is Confirmed.</title>
    <style>
        @media screen and (max-width: 600px) {
            .email-outer { padding:12px 8px !important; }
            .email-content { padding:24px 20px !important; }
            .email-greeting { font-size:24px !important; line-height:32px !important; }
            .email-qr-column { display:block !important; width:100% !important; padding:0 0 20px !important; }
            .email-instructions { display:block !important; width:100% !important; }
            .email-information th { width:30% !important; }
            .email-information td:nth-child(2) { width:16px !important; }
        }
        @media (prefers-color-scheme: dark) {
            .email-body, .email-background { background-color:#0f1724 !important; }
            .email-panel { background-color:#182333 !important; }
            .email-panel, .email-panel p, .email-information td { color:#e5edf7 !important; }
            .email-panel h1, .email-panel h2, .email-information th { color:#f4f7fc !important; }
            .email-divider, .email-information td, .email-information th { border-color:#3c4a5e !important; }
            .email-step { color:#85d9b3 !important; }
        }
        @media print {
            .email-body, .email-background, .email-panel { background-color:#ffffff !important; color:#172b3a !important; }
            .email-panel h1, .email-panel h2, .email-information th, .email-information td { color:#172b3a !important; }
            .email-qr-column { break-inside:avoid; }
        }
    </style>
</head>
<body class="email-body" style="margin:0; padding:0; background-color:#e9eff2; color:#263747; font-family:Arial, Helvetica, sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <table role="presentation" class="email-background" width="100%" cellpadding="0" cellspacing="0" border="0" lang="en" dir="ltr" style="width:100%; background-color:#e9eff2;">
        <tr>
            <td align="center" class="email-outer" style="padding:40px 16px;">
                <!--[if mso]><table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
                <table role="presentation" class="email-panel" width="640" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:640px; background-color:#ffffff; color:#263747; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:24px;">
                    <tr>
                        <td>
                            <img src="{{ url('/registration/banner/exhibitions/' . $data->banner_file) }}" width="640" alt="Exhibition banner" style="display:block; width:100%; max-width:640px; height:auto; border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td class="email-content" style="padding:28px;">
                            <h1 class="email-greeting" style="margin:0 0 16px; color:#12243a; font-size:28px; line-height:36px; font-weight:700;">Dear <b>{{ Str::Upper($data->name) }}</b></h1>
                            <p style="margin:0 0 24px; font-size:16px; line-height:25px;">Thank you for pre-registering to visit {{ $data->full_name }} - Your Online Visitor Pre-registration is Confirmed. We are pleased to confirm that your registration is successful.</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                <tr>
                                    <td class="email-divider" style="padding:22px 0 24px; border-top:2px solid #dce3e7; border-bottom:2px solid #dce3e7;">
                                        <h2 style="margin:0 0 20px; color:#12243a; font-size:24px; line-height:30px; font-weight:700;">Collecting Badge</h2>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                                            <tr>
                                                <td width="190" class="email-qr-column" style="width:190px; padding:0 24px 0 0; vertical-align:top;">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="background-color:#ffffff;">
                                                        <tr>
                                                            <td style="padding:0; background-color:#ffffff;">
                                                                <img src="{{ $message->embedData($qr_png, 'visitor-qr.png', 'image/png') }}" width="150" height="150" alt="Visitor registration QR code" style="display:block; width:150px; height:150px; border:0; background-color:#ffffff;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="email-instructions" style="vertical-align:top; font-size:16px; line-height:24px;">
                                                    <p style="margin:0 0 20px;"><b class="email-step" style="color:#14765a;">Step 1: Print / save</b> this confirmation email and bring it on the day of your visit. Ensure that the QR Code is printed clearly.</p>
                                                    <p style="margin:0;"><b class="email-step" style="color:#14765a;">Step 2: </b> Scan the barcode at Visitor <b>Self-Printing Counter</b> and collect your badge.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <h2 style="margin:22px 0 16px; color:#12243a; font-size:24px; line-height:30px; font-weight:700;">Your Information</h2>
                            <table class="email-information" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; table-layout:fixed; font-size:14px; line-height:22px; border-top:1px solid #dce3e7;">
                                <tbody>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Company Name</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->company }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Visitor Name</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Title</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->job_title }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">City</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->city }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Country</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->country }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Email</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{{ $data->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" width="160" style="width:160px; padding:10px 8px; border-bottom:1px solid #dce3e7; text-align:left; vertical-align:top; color:#334155; font-size:14px; font-weight:700;">Opening Hours</th>
                                        <td width="24" style="width:24px; padding:10px 0; border-bottom:1px solid #dce3e7; vertical-align:top;">:</td>
                                        <td style="padding:10px 8px; border-bottom:1px solid #dce3e7; vertical-align:top; overflow-wrap:anywhere; word-break:break-word;">{!! $data->opening_hours !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; margin-top:24px;">
                                <tr>
                                    <td class="email-divider" style="padding:22px 0 0; border-top:2px solid #dce3e7;">
                                        <p style="margin:0 0 22px; font-size:16px; line-height:24px;">We look forward to welcoming you at the event.</p>
                                        <p style="margin:0; font-size:16px; line-height:24px;">Yours sincerely</p>
                                        <p style="margin:0; font-size:16px; line-height:24px;">{{ $data->team }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
