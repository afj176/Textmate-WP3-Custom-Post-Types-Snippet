<?php  
 
/* 

 1st.
 Choose your post type name: ${1:posttype} 
 2nd.
 Choose your 1st Custom Taxonomy (Category Name): ${2:taxcat1} 
 3rd.
 Choose your 2nd Custom Taxonomy (Category Name): ${3:taxcat2}
 4th.
 Choose your 3rd Custom Taxonomy (Tag Name): ${4:taxtag}


*/  
 
// ************************ REGISTER POST TYPE  ********************************* // 
 

// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'my_custom_init' );

/* Here's how to create your customized labels */
function my_custom_init() { 
	\$labels = array(
		'name' => _x( '${1/./\u$0/}s', 'post type general name, usually plural' ), // Tip: _x('') is used for localization
		'singular_name' => _x( '${1/./\u$0/}', 'post type singular name' ),
		'add_new' => _x( 'Add New', '${1/./\L$0/}' ),
		'add_new_item' => __( 'Add New ${1/./\u$0/}' ),
		'edit_item' => __( 'Edit ${1/./\u$0/}' ),
		'new_item' => __( 'New ${1/./\u$0/}' ),
		'view_item' => __( 'View ${1/./\u$0/}' ),
		'search_items' => __( 'Search ${1/./\u$0/}s' ),
		'not_found' =>  __( 'No ${1/./\u$0/}s Found' ),
		'not_found_in_trash' => __( 'No ${1/./\u$0/}s Found In Trash' ),
		'parent_item_colon' => ''
	);

	// Create an array for the $args
	\$args = array( 'labels' => \$labels, /* NOTICE: the $labels variable is used here... */
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'_builtin' => false, // It's a custom post type, not built in!
		'_edit_link' => 'post.php?post=%d',
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'menu_position' => 2,
		'rewrite' => array(
		    'slug' => '${1/./\L$0/}',
		    'with_front' => FALSE,
		  ),
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	); 

	register_post_type( '${1/./\L$0/}', \$args ); /* Register it and move on */
    flush_rewrite_rules(); /*Flush rewrites for custom post type permalinks*/
}

// ************************ POST TYPE TEMPLATE REDIRECTION ********************************* //

add_action("template_redirect", 'my_template_redirect');

// Template selection
function my_template_redirect()
{
	global \$wp;
	global \$wp_query;
	if (\$wp->query_vars["post_type"] == "${1/./\L$0/}")
	{
		// Let's look for the ${1/./\L$0/}s.php template file in the current theme
		if (have_posts())
		{
			include(TEMPLATEPATH . '/${1/./\L$0/}s.php');
			die();
		}
		else
		{
			\$wp_query->is_404 = true;
		}
	}
}


  /* ********************************CUSTOM ${1/./\U$0/} POST COLUMNS***************************************** */

function edit_columns(\$columns)
{
	\$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "${1/./\u$0/} Title",
		"${1/./\L$0/}_col_description" => "Description",
		"${1/./\L$0/}_col_${2/./\L$0/}" => "${2/./\u$0/}",
		"${1/./\L$0/}_col_${3/./\L$0/}" => "${3/./\u$0/}",
		"${1/./\L$0/}_col_${4/./\L$0/}" => "${4/./\u$0/}",
	);
	
	return \$columns;
}

function custom_columns(\$column)
{
	global \$post;
	switch (\$column)
	{
		case "${1/./\L$0/}_col_description":
			the_excerpt();
			break;
		case "${1/./\L$0/}_col_${2/./\L$0/}":
			\$${2/./\L$0/}s = get_the_terms(0, "${2/./\L$0/}");
			\$${1/./\L$0/}_html = array();
			if(!\$${2/./\L$0/}s){}else{
			foreach (\$${2/./\L$0/}s as \$${2/./\L$0/})
				array_push(\$${1/./\L$0/}_html, '<a href="' . get_term_link(\$${2/./\L$0/}->slug, "${2/./\L$0/}") . '">' . \$${2/./\L$0/}->name . '</a>');
				}

			echo implode(\$${1/./\L$0/}_html, ", ");
			break;
		case "${1/./\L$0/}_col_${3/./\L$0/}":
			\$${3/./\L$0/}s = get_the_terms(0, "${3/./\L$0/}");
			\$${1/./\L$0/}_html = array();
			if(!\$${3/./\L$0/}s){}else{
			foreach (\$${3/./\L$0/}s as \$${3/./\L$0/})
				array_push(\$${1/./\L$0/}_html, '<a href="' . get_term_link(\$${3/./\L$0/}->slug, "${3/./\L$0/}") . '">' . \$${3/./\L$0/}->name . '</a>');
				}

			echo implode(\$${1/./\L$0/}_html, ", ");
			break;
		case "${1/./\L$0/}_col_${4/./\L$0/}":
			\$${4/./\L$0/}s = get_the_terms(0, "${4/./\L$0/}");
			\$${1/./\L$0/}_html = array();
			if(!\$${4/./\L$0/}s){}else{
			foreach (\$${4/./\L$0/}s as \$${4/./\L$0/})
				array_push(\$${1/./\L$0/}_html, '<a href="' . get_term_link(\$${4/./\L$0/}->slug, "${4/./\L$0/}") . '">' . \$${4/./\L$0/}->name . '</a>');
				}
			
			echo implode(\$${1/./\L$0/}_html, ", ");
			break;
	}
}
add_filter("manage_edit-${1/./\L$0/}_columns", 'edit_columns');
add_action("manage_posts_custom_column", 'custom_columns');

/* ************************************** CUSTOM TAXONOMY PARTY ****************************************** */


	// hook into the init action and call create_job_taxonomies() when it fires
	add_action( 'init', 'create_${1/./\L$0/}_taxonomies', 0 );

	// create three taxonomies, job-category industrys and position-types for the post type "job"
	function create_${1/./\L$0/}_taxonomies() {

		// Add new taxonomy, make it hierarchical (like categories)
		\$labels = array(
			'name' => _x( '${2/./\u$0/}s', 'taxonomy general name' ),
			'singular_name' => _x( '${2/./\u$0/}', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search ${2/./\u$0/}s' ),
			'all_items' => __( 'All ${2/./\u$0/}s' ),
			'parent_item' => __( 'Parent ${2/./\u$0/}' ),
			'parent_item_colon' => __( 'Parent ${2/./\u$0/}:' ),
			'edit_item' => __( 'Edit ${2/./\u$0/}' ),
			'update_item' => __( 'Update ${2/./\u$0/}' ),
			'add_new_item' => __( 'Add New ${2/./\u$0/}' ),
			'new_item_name' => __( 'New ${2/./\u$0/} Name' ),
		); 	

		register_taxonomy( '${2/./\L$0/}', array( '${1/./\L$0/}' ), array(
			'hierarchical' => true,
			'labels' => \$labels, /* NOTICE: Here is where the \$labels variable is used */
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => '${2/./\L$0/}' ),
		));

		// Add new taxonomy, hierarchical (like categories)
		\$labels = array(
			'name' => _x( '${3/./\u$0/}s', 'taxonomy general name' ),
			'singular_name' => _x( '${3/./\u$0/}', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search ${3/./\u$0/}s' ),
			'popular_items' => __( 'Popular ${3/./\u$0/}s' ),
			'all_items' => __( 'All ${3/./\u$0/}s' ),
			'parent_item' => __( 'Parent ${3/./\u$0/}' ),
			'parent_item_colon' => __( 'Parent ${3/./\u$0/}:' ),
			'edit_item' => __( 'Edit ${3/./\u$0/}' ),
			'update_item' => __( 'Update ${3/./\u$0/}' ),
			'add_new_item' => __( 'Add New ${3/./\u$0/}' ),
			'new_item_name' => __( 'New ${3/./\u$0/} Name' )
		);
		register_taxonomy( '${3/./\L$0/}', '${1/./\L$0/}', array(
			'hierarchical' => true,
			'labels' => \$labels, /* NOTICE: the \$labels variable here */
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => '${3/./\L$0/}' ),
		));

				
			// Add new taxonomy, NOT hierarchical (like tags)
			\$labels = array(
				'name' => _x( '${4/./\u$0/}s', 'taxonomy general name' ),
				'singular_name' => _x( '${4/./\u$0/}', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search ${4/./\u$0/}s' ),
				'popular_items' => __( 'Popular ${4/./\u$0/}s' ),
				'all_items' => __( 'All ${4/./\u$0/}s' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit ${4/./\u$0/}' ),
				'update_item' => __( 'Update ${4/./\u$0/}' ),
				'add_new_item' => __( 'Add New ${4/./\u$0/}' ),
				'new_item_name' => __( 'New ${4/./\u$0/} Name' ),
				'separate_items_with_commas' => __( 'Separate ${4/./\L$0/}s with commas' ),
				'add_or_remove_items' => __( 'Add or remove ${4/./\L$0/}s' ),
				'choose_from_most_used' => __( 'Choose from the most used ${4/./\L$0/}s' )
			);
			register_taxonomy( '${4/./\L$0/}', '${1/./\L$0/}', array(
				'hierarchical' => false,
				'labels' => \$labels, //NOTICE: the \$labels variable here
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => '${4/./\L$0/}' ),
			));
	   
		
		
		
		
		
	} 

 ?>