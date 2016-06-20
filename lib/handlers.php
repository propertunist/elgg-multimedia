<?php


/**
 * Dispatches file pages.
 * URLs take the form of
 *  All files:       file/all
 *  User's files:    file/owner/<username>
 *  Friends' files:  file/friends/<username>
 *  View file:       file/view/<guid>/<title>
 *  New file:        file/add/<guid>
 *  Edit file:       file/edit/<guid>
 *  Group files:     file/group/<guid>/all
 *  Download:        file/download/<guid>
 *
 *
 * @param array $page
 * @return bool
 */
function multimedia_file_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$file_dir = elgg_get_plugins_path() . 'file/views/default/resources/file';
  $multimedia_dir = elgg_get_plugins_path() . 'multimedia/pages';
	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'friends':
			file_register_toggle();
			include "$file_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$file_dir/view.php";
			break;
		case 'add':
			include "$file_dir/upload.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$file_dir/edit.php";
			break;
		case 'search':
			file_register_toggle();
			include "$file_dir/search.php";
			break;
		case 'group':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'all':
			file_register_toggle();
			include "$file_dir/world.php";
			break;
		case 'download':
			set_input('guid', $page[1]);
			include "$file_dir/download.php";
			break;
		case 'play':
			set_input('guid', $page[1]);
			include "$multimedia_dir/play.php";
			break;
		default:
			return false;
	}
	return true;
}


if (!function_exists('multimedia_page_handler'))
{
 function multimedia_page_handler($page)
{
    $page[0] = (int) $page[0];
  	$entity = get_entity($page[0]);
    $autoplay = (int) get_input('autoplay',0);

	  if ($entity instanceof ElggObject)
	  {
          $subtype = $entity->getSubtype();

          if ($subtype == 'file')
          {
              elgg_set_page_owner_guid($entity->getContainerGUID());
              $container = $entity->getContainerEntity();
              $owner = $entity->getOwnerEntity();
              if ($entity->title)
                $vars['title'] = $entity->title . ' ' .  elgg_echo('by') . ' ' . $owner->name . ' | ' . elgg_get_config('sitename');
              else
                $vars['title'] = elgg_echo('multimedia:embed_title');

              $mime = $entity->mimetype;
              $base_type = substr($mime, 0, strpos($mime,'/'));
              $vars['iframe'] = true;
              $vars['entity'] = $entity;
              if ($autoplay)
                $vars['autoplay'] = $autoplay;
        	  switch ($base_type)
        	  {
        	  	case 'video':
                {
                    $vars['player'] = elgg_view('multimedia/player', $vars);
        			break;
                }
        		case 'audio':
                {
                    $vars['player'] = elgg_view('multimedia/player', $vars);
        			break;
                }
        		default:
        		{
        		    elgg_echo('multimedia:embed:invalid_code');
        		    return false;
                }
        	  }

						// pass the correct player and variable to the embed view
            echo elgg_view_resource('multimedia/embed', $vars);
        	  return true;
    	  }
          else
            return false;
	  }
	  else
  		return false;
    }
}
