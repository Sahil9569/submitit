<html><head></head>
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
                            <td colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 20px 10px; color: #3aafa9;" valign="top">
                                <table class="border table-bordered" border cellpadding="0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #252525;" valign="top">
                                                <b>Package Name</b>
                                            </th>
                                            <th colspan="1"  class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 10px; color: #252525;" valign="top">
                                                <b>Amount</b>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach($packages as $package)
                                            <tr>
                                            @if($package['type']=='submission')
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">Submission Package => {{$package['title']}}</td>
                                            @elseif($package['type']=='flash')
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">Flash Fiction Package => {{$package['title']}}</td>
                                            @elseif($package['type']=='editing')
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">Editing and Consulting Package => {{$package['title']}}</td>
                                            @elseif($package['type']=='super')
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">Super Package => {{$package['title']}}</td>
                                            @else
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">Extra Package => {{$package['title']}}</td>
                                            @endif
                                                <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                                    <p>${{$package['amount']}}</p>
                                                    @php
                                                        $total = $total + $package['amount']
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                        <td colspan="1" class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 20px 10px; color: #000;" valign="top">
                                                    <p>Sub-Total: ${{$total}}</p>
                                                </td>
                        </tr>
						<tr>
                            <td class="content-block"  style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; line-height:26px; vertical-align: top; margin: 0; padding: 0 20px 30px;" valign="top">
                                <a href="{{env('VITE_VUE_APP_URL')}}/payment/{{$payment['paymentLink']}}"  style="color:#3aafa9;"  style="margin:0;"> Please Follow this link for payment </a> 
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
    
</body></html>