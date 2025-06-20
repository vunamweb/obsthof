<?php
// Text
$_['text_paypal']							= 'PayPal';
$_['text_paypal_title']						= 'PayPal (PayPal, Kreditkarte, Sofortüberweisung)';
$_['text_paypal_paylater_title']			= 'Jetzt kaufen, später bezahlen mit PayPal';
$_['text_paypal_googlepay_title']			= 'Google Pay';
$_['text_paypal_applepay_title']			= 'Apple Pay';
$_['text_checkout_payment_address']  		= 'Details zur Rechnungsstellung';
$_['text_checkout_shipping_address'] 		= 'Details zur Lieferung';
$_['text_checkout_shipping_method']  		= 'Liefermethode';
$_['text_checkout_payment_method']  		= 'Payment Method';
$_['text_your_details']              		= 'Ihre persönlichen Daten';
$_['text_your_address']              		= 'Ihre Adresse';
$_['text_cart']               				= 'Einkaufswagen';
$_['text_shipping_updated']   				= 'Versanddienst aktualisiert';
$_['text_order_message']					= 'PayPal Seller Protection - %s';
$_['text_day']                 				= 'Tag';
$_['text_week']                				= 'Woche';
$_['text_semi_month']          				= 'Halber Monat';
$_['text_month']               				= 'Monat';
$_['text_year']                				= 'Jahr';
$_['text_trial']               				= '%s every %s %s for %s payments then ';
$_['text_recurring']          				= '%s every %s %s';
$_['text_recurring_item']      				= 'Recurring Item';
$_['text_payment_recurring']   				= 'Payment Profile';
$_['text_trial_description']   				= '%s every %d %s(s) for %d payment(s) then';
$_['text_payment_description'] 				= '%s every %d %s(s) for %d payment(s)';
$_['text_payment_cancel']      				= '%s every %d %s(s) until canceled';
$_['text_order_message']					= 'PayPal Seller Protection - %s';
$_['text_wait']								= 'Bitte warten!';
$_['text_loading']          				= 'Loading...';

// Column
$_['column_image']             				= 'Bild';
$_['column_name']              				= 'Produkt';
$_['column_model']             				= 'Model';
$_['column_quantity']          				= 'Menge';
$_['column_price']             				= 'Einzelpreis';
$_['column_total']             				= 'Summe';

// Entry
$_['entry_email']                    		= 'E-Mail';
$_['entry_firstname']                		= 'Vorname';
$_['entry_lastname']                 		= 'Nachname';
$_['entry_telephone']               		= 'Telefon';
$_['entry_company']                  		= 'Unternehmen';
$_['entry_address_1']                		= 'Addresse 1';
$_['entry_address_2']                		= 'Addresse 2';
$_['entry_postcode']                 		= 'PLZ';
$_['entry_city']                     		= 'Stadt';
$_['entry_country']                  		= 'Land';
$_['entry_zone']                    		= 'Region';
$_['entry_card_number']						= 'Kartennummer';
$_['entry_expiration_date']					= 'Ablaufdatum';
$_['entry_cvv']								= 'CVV';

// Button
$_['button_confirm']  						= 'Confirm';
$_['button_shipping'] 						= 'Update shipping';
$_['button_pay']							= 'Pay with Card';

// Error
$_['error_warning']							= 'Please check the form carefully for errors.';
$_['error_stock']              				= 'Products marked with *** are not available in the desired quantity or not in stock!';
$_['error_minimum']            				= 'Minimum order amount for %s is %s!';
$_['error_required']           				= '%s required!';
$_['error_product']            				= 'Warning: There are no products in your cart!';
$_['error_recurring_required'] 				= 'Please select a payment recurring!';
$_['error_unavailable'] 	  				= 'Please use the full checkout with this order!';
$_['error_shipping']                 		= 'Warning: Shipping method required!';
$_['error_no_shipping']    					= 'Warning: No Shipping options are available.';
$_['error_firstname']                		= 'First Name must be between 1 and 32 characters!';
$_['error_lastname']                 		= 'Last Name must be between 1 and 32 characters!';
$_['error_email']                    		= 'E-Mail address does not appear to be valid!';
$_['error_telephone']                		= 'Telephone must be between 3 and 32 characters!';
$_['error_password']                 		= 'Password must be between 4 and 20 characters!';
$_['error_confirm']                  		= 'Password confirmation does not match password!';
$_['error_address_1']                		= 'Address 1 must be between 3 and 128 characters!';
$_['error_city']                     		= 'City must be between 2 and 128 characters!';
$_['error_postcode']                 		= 'Postcode must be between 2 and 10 characters!';
$_['error_country']                  		= 'Please select a country!';
$_['error_zone']                     		= 'Please select a region / state!';
$_['error_agree']                    		= 'Warning: You must agree to the %s!';
$_['error_address']                  		= 'Warning: You must select address!';
$_['error_custom_field']             		= '%s required!';
$_['error_order_voided']					= 'We could not process your payment. All purchase units in the order are voided. Please <a href="%s" target="_blank">contact us</a>.';
$_['error_order_completed']					= 'We could not process your payment. The payment was authorized or the authorized payment was captured for the order. Please <a href="%s" target="_blank">contact us</a>.';
$_['error_authorization_captured']			= 'We could not process your payment. The authorized payment has one or more captures against it. The sum of these captured payments is greater than the amount of the original authorized payment. Please <a href="%s" target="_blank">contact us</a>.';
$_['error_authorization_denied']			= 'We could not process the transaction with this card. The funds could not be captured. Please try a different funding source.';
$_['error_authorization_expired']			= 'We could not process your payment. The authorized payment has expired. Please <a href="%s" target="_blank">contact us</a>.';
$_['error_capture_declined']				= 'We could not process the transaction with this card. The funds could not be captured. Please try a different funding source.';
$_['error_capture_failed']					= 'We could not process your payment. There was an error while capturing payment. Please <a href="%s" target="_blank">contact us</a>.';
$_['error_3ds_failed_authentication']		= 'We could not process the transaction with this card. You may have failed the challenge or the device was not verified.';
$_['error_3ds_rejected_authentication']		= 'We could not process the transaction with this card. 3D Secure authentication was skipped by you.';
$_['error_3ds_attempted_authentication'] 	= 'We could not process the transaction with this card. Card is not enrolled in 3D Secure as card issuing bank is not participating in 3D Secure.';
$_['error_3ds_unable_authentication']		= 'We could not process the transaction with this card. Issuing bank is not able to complete authentication.';
$_['error_3ds_challenge_authentication']	= 'We could not process the transaction with this card. Challenge required for authentication.';
$_['error_3ds_card_ineligible']				= 'We could not process the transaction with this card. Card type and issuing bank are not ready to complete a 3D Secure authentication.';
$_['error_3ds_system_unavailable']			= 'We could not process the transaction with this card. An error occurred with the 3D Secure authentication system.';
$_['error_3ds_system_bypassed'] 			= 'We could not process the transaction with this card. 3D Secure was skipped as authentication system did not require a challenge.';
$_['error_payment']							= 'Please choose another payment method or <a href="%s" target="_blank">contact us</a>.';
$_['error_timeout'] 	  					= 'Sorry, PayPal is currently busy. Please try again later!';
