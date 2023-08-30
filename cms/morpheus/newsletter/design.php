<?php
global $morpheus, $dir, $navID;

$html = '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<!--<![endif]-->
	<title>#title#</title>
	<!--[if mso]>
	<style type="text/css">
    table {border-collapse:collapse;border:0;border-spacing:0;margin:0;}
    div, td {padding:0;}
    div {margin:0 !important;}
	</style>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
	<style type="text/css">
		h1 {margin-top:0;margin-bottom:12px;font-family:Verdana,Helvetica,Arial;font-size:20px;line-height:28px;font-weight:normal;color:#0072BC;}
		h2,h3 {margin-top:0;margin-bottom:12px;font-family:Verdana,Helvetica,Arial;font-size:14px;line-height:28px;font-weight:bold;color:#0072BC;}
		h3 {font-size:14px;line-height:26px;font-weight:bold;color:#0072BC;}
		p, ul, a, td, div {margin:0;font-family:Verdana,Helvetica,Arial;font-size:14px;line-height:21px;margin-bottom:18px;color:#000;}
		.btn { background:#0072BC;color:#fff;padding: 8px 20px;text-decoration:none; border-radius:8px;margin-top:24px;margin-bottom:24px;}
		hr { border-bottom: solid 1px #0072BC; margin-top:24px;margin-bottom:0;display:block;background:transparent; }
		@media screen and (max-width: 350px) {
			.three-col .column {
				max-width: 100% !important;
			}
		}
		@media screen and (min-width: 351px) and (max-width: 460px) {
			.three-col .column {
				max-width: 50% !important;
			}
		}
		@media screen and (max-width: 460px) {
			.two-col .column {
				max-width: 100% !important;
			}
			.two-col img {
				width: 100% !important;
			}
		}
		@media screen and (min-width: 461px) {
			.three-col .column {
				max-width: 33.3% !important;
			}
			.two-col .column {
				max-width: 50% !important;
			}
			.two-col .column-1 {
				max-width: 25% !important;
			}
			.two-col .column-2 {
				max-width: 75% !important;
			}
			.sidebar .small {
				max-width: 16% !important;
			}
			.sidebar .large {
				max-width: 84% !important;
			}
		}
	</style>
</head>

<body style="margin:0;padding:0;word-spacing:normal;background-color:#ffffff;">
	<div style="display: none !important; mso-hide:all; font-size:0; max-height:0; line-height:0; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
		#preheader#
	</div>
	
	<div role="article" aria-roledescription="email" lang="en" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#ffffff;">
		<table role="presentation" style="width:100%;border:0;border-spacing:0;">
			<tr>
				<td align="center">
					<!--[if mso]>
          <table role="presentation" align="center" style="width:800px;">
          <tr>
          <td>
          <![endif]-->
					<div class="outer" style="max-width:800px;">
						<table role="presentation" style="width:100%;border:0;border-spacing:0;">
						<tr>
							<td style="padding:0px;width:100%;margin-bottom:0;" valign="bottom">
								<p style="font-size:20px; color:#ddd;text-transform:uppercase;">F&uuml;r die Menschen. Mit den Menschen.</p>
								<img src="'.$morpheus["url"].'mthumb.php?src=images/spacer.png&w=1&amp;vid=#uid#&nl=#nlid#">
							</td>
							<td style="padding:0px;width:100%;margin-bottom:0;">
								<img src="'.$morpheus["url"].'images/Logo_API.svg" alt="API Logo" class="logo" style="width:150px;">
							</td>
						</tr>
						</table>

						#here_comes_the_message#						

						<div class="spacer" style="line-height:24px;height:24px;mso-line-height-rule:exactly;">&nbsp;</div>

																			
						<table role="presentation" style="width:100%;border:0;border-spacing:0;background:#efefef;">
							<tr>
								<td style="padding:10px;text-align:center;width:100%">
									<p style="margin:0;font-family:Verdana,Helvetica,Arial;font-size:13px;line-height:20px;">
										&nbsp;<br>
										<b>Adler-Pollak-Institut für Individualpsychologie</b><br>
										Inhaber: Peter Pollak<br>
										Rheinstr. 24<br>
										55283 Nierstein<br>
										<br>
										Deine Ansprechpartnerin: Melanie Grießhaber<br>
										Mobil: +49 1573 54 98 000<br>
										E-Mail: info@adler-pollak.institut.de<br>
										Web: <a href="'.$morpheus["url"].'adler.php?uid=#uid#&nl=#nlid#&il='.$morpheus["url"].'" style="text-decoration:none;color:#000000;">www.adler-pollak-institut.de</a><br>
										<br>
										Copyright © 2022 Peter Pollak und Melanie Grießhaber, Alle Rechte vorbehalten.<br>
										Du erhältst diese E-Mail, weil du dich zum Newsletter angemeldet hast.<br>
										Wenn du dich abmelden möchtest, klicke <a href="'.$morpheus["url"].'adler.php?uid=#uid#&nl=#nlid#&il='.$morpheus["url"].$navID[34].'" style="text-decoration:none;color:#000000;">hier</a><br/>
										<br/>
										<a href="'.$morpheus["url"].'adler.php?uid=#uid#&nl=#nlid#&il='.$morpheus["url"].$navID[10].'" style="text-decoration:none;color:#000000;">Impressum</a> | 
										<a href="'.$morpheus["url"].'adler.php?uid=#uid#&nl=#nlid#&il='.$morpheus["url"].$navID[11].'" style="text-decoration:none;color:#000000;">Datenschutz</a><br>
									</p>
								</td>
							</tr>
						</table>		


					</div>
					<!--[if mso]>
          </td>
          </tr>
          </table>
          <![endif]-->
				</td>
			</tr>
		</table>
	</div>
</body>

</html>';