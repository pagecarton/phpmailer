<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Table_Sample
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SendMailSettings.php Sunday 26th of September 2021 06:29PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class SendMailSettings extends PageCarton_Settings
{
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		if( ! $settings = unserialize( @$values['settings'] ) )
		{
			if( is_array( $values['data'] ) )
			{
				$settings = $values['data'];
			}
			elseif( is_array( $values['settings'] ) )
			{
				$settings = $values['settings'];
			}
			else
			{
				$settings = $values;
			}
		}
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;



        //  Sample Text Field Retrieving E-mail Address
		$fieldset->addElement( array( 'name' => 'from', 'label' => 'From E-mail Address', 'placeholder' => 'e.g. email@example.com', 'value' => @$settings['from'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'server', 'label' => 'SMTP Server', 'placeholder' => 'e.g. smtp.example.com', 'value' => @$settings['server'], 'type' => 'InputText' ) );
        $fieldset->addElement( array( 'name' => 'port', 'label' => 'SMTP Port', 'placeholder' => 'e.g. 465', 'value' => @$settings['port'], 'type' => 'InputText' ) );

		$fieldset->addElement( array( 'name' => 'username', 'label' => 'SMTP Username', 'placeholder' => 'e.g. email@example.com', 'value' => @$settings['username'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'password', 'label' => 'SMTP Password', 'placeholder' => '********', 'value' => @$settings['password'], 'type' => 'password' ) );

		
		$fieldset->addLegend( 'SMTP Email Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
