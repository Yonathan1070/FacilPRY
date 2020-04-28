<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Recuperar Contraseña | InkBrutalPRY</title>
	<meta name="viewport" content="width=device-width" />
	
    <style type="text/css">
        @media only screen and (max-width: 550px),
        screen and (max-device-width: 550px) {
            body[yahoo] .buttonwrapper {
                background-color: transparent !important;
            }

            body[yahoo] .button {
                padding: 0 !important;
            }

            body[yahoo] .button a {
                background-color: #9b59b6;
                padding: 15px 25px !important;
            }
        }

        @media only screen and (min-device-width: 601px) {
            .content {
                width: 600px !important;
            }

            .col387 {
                width: 387px !important;
            }
        }
    </style>
</head>

<body bgcolor="#ffffff" style="margin: 0; padding: 0;" yahoo="fix">
    <table align="center" border="0" cellpadding="0" cellspacing="0"
        style="border-collapse: collapse; width: 100%; max-width: 600px;" class="content">
        <tr>
            <td style="padding: 15px 10px 15px 10px;"></td>
        </tr>
        <tr>
            <td align="center" bgcolor="#131426"
                style="padding: 20px 20px 0px 20px; color: #ffffff; font-family: Arial, sans-serif; font-size: 36px; font-weight: bold;">
                <img src="{{asset("assets/images/LOGO_INK_BLANCO.png")}}" alt="Ink Logo" width="100"/>
                <img src="{{asset("assets/images/LOGO_BRUTAL_BLANCO.png")}}" alt="Brutal Logo" width="150"/>
            </td>
        </tr>
        <tr>
            <table align="center" border="0" cellpadding="0" cellspacing="0"
                style="border-collapse: collapse; width: 100%; max-width: 600px;" class="content">
                <tr>
                    <td align="center" bgcolor="#d8d8d4"
                        style="padding: 40px 20px 0px 20px; color: #555555; font-family: Arial, sans-serif; font-size: 20px; line-height: 30px; border-bottom: 1px solid #d8d8d4;">
                        <b>¿Olvidaste tu Contraseña? ¡Cambiala ahora!</b>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#d8d8d4"
                        style="padding: 20px 20px 0 20px; color: #555555; font-family: Arial, sans-serif; font-size: 20px; line-height: 30px;">
                        <b>Nombre de Usuario: </b> {{$user->USR_Nombre_Usuario}}
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#d8d8d4"
                        style="padding: 30px 20px 10px 20px; font-family: Arial, sans-serif; border-bottom: 1px solid #d8d8d4;">
                        <table bgcolor="#E63E26" border="0" cellspacing="0" cellpadding="0" class="buttonwrapper">
                            <tr>
                                <td align="center" height="50"
                                    style=" padding: 0 25px 0 25px; font-family: Arial, sans-serif; font-size: 16px; font-weight: bold;"
                                    class="button">
                                    <a href="{{route('cambiar_clave', ['token' => $token])}}" style="color: #ffffff; text-align: center; text-decoration: none;">
                                        Cambiar Contraseña
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#131426"
                        style="padding: 15px 10px 20px 10px; color: #fff; font-family: Arial, sans-serif; font-size: 15px; line-height: 18px;">
                        <b>
                            <a href="https://www.inkdigital.co/" target="_blank" style="text-decoration:none; color: #fff;">
                                Ink Agencia Digital
                            </a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="https://www.brutaldigitalagency.com/" target="_blank" style="text-decoration:none; color: #fff;">
                                Brutal Digital Agency
                            </a>
                        </b><br /><br />
                            <a href="https://www.facebook.com/inkagenciad" target="_blank" style="text-decoration:none; color: #fff;">
                                Facebook
                            </a>
                        &bull; 
                            <a href="https://twitter.com/brutalddm" target="_blank" style="text-decoration:none; color: #fff;">
                                Twitter
                            </a>
                        &bull; 
                            <a href="https://www.instagram.com/inkagenciad/" target="_blank" style="text-decoration:none; color: #fff;">
                                Instagram
                            </a>
                        &bull; 
                        Cra 11B N° 99 - 25 Bogotá
                        &bull; 
                        (57)322 231 11 41
                    </td>
                </tr>
            </table>
        </tr>
    </table>
</body>

</html>