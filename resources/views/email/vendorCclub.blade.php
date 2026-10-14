<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title></title>
    
     <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<style type="text/css">
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: 'Poppins', sans-serif;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        #button a {
            color: #FFFFFF;
        }

        a:link img {
            display: block;
        }

        body {
            margin: 0;
            padding: 0;
            text-align: left !important;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        a img {
            border: none !important;
        }

        body {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

    </style>
</head>

<body>

    <table style="border: 0px solid #ddd;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding:0 30px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#fff" class="main">
        <tbody>
            <!--==============banner start here===============-->
            <tr>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>

                                <td align="center" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                    <a target="_blank" href="#" style="border-bottom: 1px solid #19a7d6;color: #4D008D;text-decoration: none;text-transform: uppercase;font-size: 31px;padding: 20px;display: block;font-weight: 600;letter-spacing: 2px;">
                                        <img style="width:200px;" src="https://tolocal.shop/peter/assets/client/images/dark_logo.png">
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr style="background: #f1f1f1;">
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>

                                <td align="center" style="padding: 0;">
                                    <h2 style="font-size: 20px; font-weight: 400;">
                                        
                                       
                                        User - @php
                                        $directoryData = Helper::getUserName($data['cc_user_id']);
                                        echo $directoryData[0]->name;
                                        @endphp is Now a Member Of The Customer Club.
                                       
                                        <br>The information is as follows:
                                    </h2>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table>

                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>Company :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                @php
                                $directoryData = Helper::getDirectory($data['cc_directory_id']);
                                echo $directoryData[0]->title;

                                $country = Helper::getCountryName($data['country']);
                                $state = Helper::getStateName($data['state']);
                                if (is_numeric($data['city'])) {
                                    $cityObj = Helper::getCityName($data['city']);
                                    $city = $cityObj && isset($cityObj->name) ? $cityObj->name : '';
                                } else {
                                    $city = $data['city'];
                                }
                                @endphp
                            </td>
                        </tr>
                        
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> Name :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{$data['customer_club_name'] ?? 'N/A'}} {{$data['customer_club_lname'] ?? 'N/A'}}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> Phone :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{$data['customer_club_phone'] ?? 'N/A'}}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> Email :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{$data['customer_club_email'] ?? 'N/A'}}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> Country :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                 {{ $country->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> State :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                 {{ $state->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong> City :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{ $city }}
                            </td>
                        </tr>
                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>Visitor's Mssage :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{$data['customer_club_message'] ?? 'N/A'}}
                            </td>
                        </tr>

                        <tr>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>View all Customers:</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                <a href="{{url('company/customer/club')}}" style="color: #fff; padding: 5px 15px; background: #000; border-radius: 5px; display: inline-block; text-decoration: none;">
                                    View Customers in Customer Club
                                </a>
                            </td>
                        </tr>



                    </table>
                </td>
            </tr>

            <tr>
                <!--==============logo start here===============-->
                <td style="background: #203864;font-size: 12px;color: #fff;text-align: center;border-top: 1px solid #19a7d6;">
                    <p style="display: inline-block;padding-right: 30px;"><b>Contacts</b> : <a style="color: #fff;" href="{{ route('contact') }}">+{{ env('APP_CONTACT') }} </a></p>
                    <p style="display: inline-block;"><b>Email </b>: {{ env('APP_EMAIL') }} </p>
                </td>
                <!--==============logo end here===============-->
            </tr>

            <!--==============footer end here===============-->
    </table>

</body>

</html>
