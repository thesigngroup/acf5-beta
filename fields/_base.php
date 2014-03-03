<?php

class acf_field {
	
	var $name,
		$title,
		$category,
		$defaults,
		$l10n;
	
	
	/*
	*  __construct()
	*
	*  Adds neccessary Actions / Filters
	*
	*  @since	3.6
	*  @date	30/01/13
	*/
	
	function __construct()
	{
		// register field
		add_filter('acf/get_field_types', array($this, 'get_field_types'), 10, 1);
		add_filter('acf/get_valid_field/type=' . $this->name, array($this, 'get_valid_field'), 10, 1);
		
		
		// value
		$this->add_filter('acf/load_value/type=' . $this->name, array($this, 'load_value'), 10, 3);
		$this->add_filter('acf/update_value/type=' . $this->name, array($this, 'update_value'), 10, 3);
		$this->add_filter('acf/delete_value/type=' . $this->name, array($this, 'delete_value'), 10, 3);
		$this->add_filter('acf/format_value/type=' . $this->name, array($this, 'format_value'), 10, 4);
		$this->add_filter('acf/validate_value/type=' . $this->name, array($this, 'validate_value'), 10, 4);
		
		
		// field
		$this->add_filter('acf/load_field/type=' . $this->name, array($this, 'load_field'), 10, 3);
		$this->add_filter('acf/update_field/type=' . $this->name, array($this, 'update_field'), 10, 1);
		$this->add_filter('acf/delete_field/type=' . $this->name, array($this, 'delete_field'), 10, 2);
		$this->add_action('acf/render_field/type=' . $this->name, array($this, 'render_field'), 10, 1);
		$this->add_action('acf/render_field_options/type=' . $this->name, array($this, 'render_field_options'), 10, 1);
		
		
		// input actions
		$this->add_action('acf/input/admin_enqueue_scripts', array($this, 'input_admin_enqueue_scripts'), 10, 0);
		$this->add_action('acf/input/admin_head', array($this, 'input_admin_head'), 10, 0);
		$this->add_filter('acf/input/admin_l10n', array($this, 'input_admin_l10n'), 10, 1);
		$this->add_action('acf/input/admin_footer', array($this, 'input_admin_footer'), 10, 1);
		
		
		// field group actions
		$this->add_action('acf/field_group/admin_enqueue_scripts', array($this, 'field_group_admin_enqueue_scripts'), 10, 0);
		$this->add_action('acf/field_group/admin_head', array($this, 'field_group_admin_head'), 10, 0);
		
	}
	
	
	/*
	*  add_filter
	*
	*  @description: checks if the function is_callable before adding the filter
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
	{
		if( is_callable($function_to_add) )
		{
			add_filter($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	
	/*
	*  add_action
	*
	*  @description: checks if the function is_callable before adding the action
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
	{
		if( is_callable($function_to_add) )
		{
			add_action($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	
	/*
	*  get_field_types()
	*
	*  Adds this field to the select list when creating a new field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$fields	- the array of all registered fields
	*
	*  @return	$fields - the array of all registered fields
	*/
	
	function get_field_types( $fields )
	{
		// defaults
		if( !$this->category )
		{
			$this->category = __('Basic', 'acf');
		}
		
		
		// add to array
		$fields[ $this->category ][ $this->name ] = $this->label;
		
		
		// return array
		return $fields;
	}
	
	
	/*
	*  get_valid_field
	*
	*  action called when rendering the head of an admin screen. Used primarily for passing PHP to JS
	*
	*  @type	filer
	*  @date	1/06/13
	*
	*  @param	$field	{array}
	*  @return	$field	{array}
	*/
	
	function get_valid_field( $field )
	{
		if( !empty($this->defaults) )
		{
			foreach( $this->defaults as $k => $v )
			{
				if( !isset($field[ $k ]) )
				{
					$field[ $k ] = $v;
				}
			}
		}
		
		return $field;
	}
	
	
	/*
	*  admin_l10n
	*
	*  filter is called to load all l10n text translations into the admin head script tag
	*
	*  @type	filer
	*  @date	1/06/13
	*
	*  @param	$field	{array}
	*  @return	$field	{array}
	*/
	
	function input_admin_l10n( $l10n )
	{
		if( !empty($this->l10n) )
		{
			$l10n[ $this->name ] = $this->l10n;
		}
		
		return $l10n;
	}
	
	
}

?>