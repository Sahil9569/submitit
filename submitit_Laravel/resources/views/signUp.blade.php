<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>Submitit</title>
</head>
<body>
<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="http://schema.org/ConfirmAction" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;  border: 0;">
            <tbody><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td align="center" class="content-wrap" valign="top">
                    <meta itemprop="name" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <table width="600" cellpadding="0" cellspacing="0" style="border:1px solid #eee; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <tbody>
                        <tr>
                            <td align="center"  style="background-color: #17252a; padding: 20px 0; box-shadow: 0 5px 18px 0px rgba(0,0,0,8%);">
                                <a href="#">
                                    <img src="{{env('APP_URL')}}/images/logo-header.png" style="width:160px;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 40px 20px 10px; color: #3aafa9;" valign="top">
                                <table>
                                    <tr>
                                        <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #3aafa9;" valign="top"><p><b>Full Name :</b></p>
                                        </td>
                                        <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                            <p>{{$user['firstName']}} {{$user['lastName']}}</p>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #3aafa9;" valign="top"><p><b>Phone Number :</b></p>
                                        </td>
                                        <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                            <p>{{$user['phone']}}</p>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #3aafa9;" valign="top"><p><b>Email :</b></p>
                                        </td>
                                        <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                            <p>{{$user['email']}}</p>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #3aafa9;" valign="top"><p><b>Address :</b></p>
                                        </td>
                                        <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                            <p>{{$user['street']}}, {{$user['city']}}, {{$user['state']}}</p>
                                        </td> 
                                    </tr>
                                </table>
                                  
                            </td>
                            
                        </tr>
                        <tr>
                            <td class="content-block"  style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; line-height:26px; vertical-align: top; margin: 0; padding: 0 20px 5px;" valign="top">
                                <p style="margin:0;">Shared Email : 
                                @if($user['sharedEmail'])
                                    <strong>{{$user['sharedEmail']}}</strong>
                                @else
                                    <strong>NULL</strong>
                                @endif
                                </p> 
                            </td>
                        </tr>
						<tr>
                            <td class="content-block"  style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; line-height:26px; vertical-align: top; margin: 0; padding: 0 20px 5px;" valign="top">
                                <p style="margin:0;"> Professional Email : <strong>{{$user['email']}}</strong> </p> 
                            </td>
                        </tr>
						<tr>
                            <td class="content-block"  style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; line-height:26px; vertical-align: top; margin: 0; padding: 0 20px 30px;" valign="top">
                                <p style="margin:0;"> Password : <strong>{{$password}}</strong> </p> 
                            </td>
                        </tr>
                        
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-block" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 20px 20px;" valign="top">
                                <p style="margin:0; padding-bottom:5px;color:#252525;"><strong>Best </strong></p>
                                <p style="margin:0; padding-bottom:5px;color:#252525;"><strong>Submitit </strong></p>
                            </td>
                        </tr>
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <td class="content-block" style="text-align: center;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 15px 20px;background-color:#eff2f7;" valign="top">
                                © 2023 Submitit
                            </td>
                        </tr>
                    </tbody></table>
                </td>
            </tr>
        </tbody></table>
</body>
</html>