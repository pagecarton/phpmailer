<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PHPMailer_SendMail
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SendMail.php Sunday 26th of September 2021 01:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */



//Import PHPMailer classes into the global namespace
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
ini_set( 'display_errors', "1" );


class SendMail extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Send Mail'; 

    
    /**
     * 
     * 
     */
	public static function splitEmailParts( $emailFormat )
    {
        $name = null;
        $email = $emailFormat;
        if( strpos( $emailFormat, '<' ) )
        {
            list( $name, $email ) = explode( '<', $emailFormat );
            $name = trim( $name, '"\' ' );
            $email = trim( $email, "<> " );
        }
        return array( 'name' => $name, 'email' => $email );
    }
    
    /**
     * Performs the whole widget running process
     * 
     */
	public static function send( $mailInfo )
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        //Set the hostname of the mail server
        $mail->Host = SendMailSettings::retrieve( 'server');
        //Use `$mail->Host = gethostbyname('smtp.gmail.com');`
        //if your network does not support SMTP over IPv6,
        //though this may cause issues with TLS

        //Set the SMTP port number:
        // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
        // - 587 for SMTP+STARTTLS
        $mail->Port = intval( SendMailSettings::retrieve( 'port') ? : 465 );

        //Set the encryption mechanism to use:
        // - SMTPS (implicit TLS on port 465) or
        // - STARTTLS (explicit TLS on port 587)
        switch( $mail->Port )
        {
            case 465:
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            break;
            case 587:
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            break;

        }

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = SendMailSettings::retrieve( 'username');

        //Password to use for SMTP authentication
        $mail->Password = SendMailSettings::retrieve( 'password');

        //Set who the message is to be sent from
        //Note that with gmail you can only use your account address (same as `Username`)
        //or predefined aliases that you have configured within your account.
        //Do not use user-submitted addresses in here
        $pts = self::splitEmailParts( $mailInfo['from'] );

        if( SendMailSettings::retrieve( 'from' ) )
        {
            $pts['email'] = SendMailSettings::retrieve( 'from' );
            $mailInfo['return-path'] = $pts['email'];
        }

        $mail->setFrom( $pts['email'], $pts['name']);

        foreach( explode( ',', $mailInfo['to'] ) as $each )
        {
            $pts = self::splitEmailParts( $each );
            
            //Set who the message is to be sent to
            $mail->addAddress( $pts['email'], $pts['name'] );
        }

        $pts = self::splitEmailParts( $mailInfo['return-path'] );

        //Set an alternative reply-to address
        //This is a good place to put user-submitted addresses
        $mail->addReplyTo( $pts['email'], $pts['name'] );


        //Set the subject line
        $mail->Subject = $mailInfo['subject'];

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML( $mailInfo['body'], __DIR__);

        //Replace the plain text body with one created manually
        $mail->AltBody = strip_tags( $mailInfo['body'] );

        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (! $sent = $mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message sent!';
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }

        return $sent;

    }

    
    /**
     * Performs the whole widget running process
     * 
     */
	public static function hook( $object, $method, & $data )
    {
		try
		{ 
            switch( strtolower( $method ) )
            {
                case 'sendmail':
                    if( self::send( $data ) )
                    {
                        $data['sent'] = true;
                    }
                break;
            }
		}  
		catch( Exception $e )
        { 
            return false; 
        }

    }

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            //  Output demo content to screen
             $this->setViewContent( self::__( '<h1>Hello PageCarton Widget</h1>' ) ); 
             $this->setViewContent( self::__( '<p>Customize this widget (' . __CLASS__ . ') by editing this file below:</p>' ) ); 
             $this->setViewContent( self::__( '<p style="font-size:smaller;">' . __FILE__ . '</p>' ) ); 
             //if( self::send( array( 'body' => 'THIS IS MY MESSAGE NOW' ) ) )
             {
                //$data['sent'] = true;
             }


             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
