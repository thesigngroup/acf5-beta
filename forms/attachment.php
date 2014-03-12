<?php 

class acf_controller_attachment {
	
	/*
	*  Constructor
	*
	*  This function will construct all the neccessary actions and filters
	*
	*  @type	function
	*  @date	23/06/12
	*  @since	3.1.8
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function __construct()
	{
		// actions
		add_filter('attachment_fields_to_edit', array($this, 'attachment_fields_to_edit'), 10, 2);
		add_filter('attachment_fields_to_save', array($this, 'save_attachment'), 10, 2);
		
		add_action('admin_enqueue_scripts',		array( $this, 'admin_enqueue_scripts' ));
		
		
	}
	
	
	/*
	*  validate_page
	*
	*  This function will check if the current page is for a post/page edit form
	*
	*  @type	function
	*  @date	23/06/12
	*  @since	3.1.8
	*
	*  @param	n/a
	*  @return	(boolean)
	*/
	
	function validate_page()
	{
		// global
		global $typenow;
		
		
		// vars
		$return = false;
		
		
		if( $typenow === 'attachment' ) {
			
			$return = true;
			
		}
						
		
		// return
		return $return;
	}
	
	
	/*
	*  admin_enqueue_scripts
	*
	*  This action is run after post query but before any admin script / head actions. 
	*  It is a good place to register all actions.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @date	26/01/13
	*  @since	3.6.0
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function admin_enqueue_scripts() {
		
		// validate page
		if( !$this->validate_page() ) {
			
			return;
			
		}
		
		
		// load acf scripts
		acf_enqueue_scripts();
		
	}
	
	
	/*
	*  attachment_fields_to_edit
	*
	*  description
	*
	*  @type	function
	*  @date	8/10/13
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function attachment_fields_to_edit( $form_fields, $post ) {
		
		// vars
		$el = 'tr';
		$post_id = $post->ID;
		$args = array(
			'attachment' => 'All'
		);
		
		
		// $el
		if( $this->validate_page() ) {
			
			$el = 'div';
			
		}
		
		// get field groups
		$field_groups = acf_get_field_groups( $args );
		
		
		// render
		if( !empty($field_groups) ) {
			
			// get acf_form_data
			ob_start();
			
			
			acf_form_data(array( 
				'post_id'	=> $post_id, 
				'nonce'		=> 'attachment',
			));
			
			
			foreach( $field_groups as $field_group ) {
				
				$fields = acf_get_fields( $field_group );
				
				acf_render_fields( $post_id, $fields, $el, 'field' );
				
			}
			
			
			$html = ob_get_contents();
			
			
			ob_end_clean();
			
			
			$form_fields[ 'acf' ] = array(
	       		'label' => '',
	   			'input' => 'html',
	   			'html' => $html
			);

			
			/*

			foreach( $field_groups as $field_group ) {
				
				$fields = acf_get_fields( $field_group );
				
				if( !empty($fields) ) {
					
					foreach( $fields as $field ) {
						
						// load value
						if( $post_id && empty($field['value']) )
						{
							$field['value'] = acf_get_value( $post_id, $field, true );
						} 
						
						
						// set prefix for correct post name (prefix + key)
						$field['prefix'] = 'acf';
						
						
						// get acf_form_data
						ob_start();
						
							acf_form_data(array( 
								'post_id'	=> $post_id, 
								'nonce'		=> 'attachment',
							));
						
							$html = ob_get_contents();
						
						ob_end_clean();
						
					}
					
				}
				
				
				
			}
*/
			
		}
		
		
		// return
		return $form_fields;
		
	}
	
	
	/*
	*  save_attachment
	*
	*  description
	*
	*  @type	function
	*  @date	8/10/13
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function save_attachment( $post, $attachment ) {
		
		// verify and remove nonce
		if( ! acf_verify_nonce('attachment') )
		{
			return $post;
		}
		
	    
	    // save data
	    if( acf_validate_save_post(true) )
		{
			acf_save_post( $post['ID'] );
		}
		
		
		// return
		return $post;
			
	}
	
			
}

new acf_controller_attachment();

?>