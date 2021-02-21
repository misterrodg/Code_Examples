<?php
namespace App\models;
/*
  Uses
  Includes
  Etc
*/

class content extends Model {
  protected $table = 'content';

  // Get content by primary key in DB (auto increment int)
	public static function contentByID( $contentid ){
	  $result = DB::table('content')
      ->where('contentid',$contentid)
      ->limit(1)
      ->first();
	  return $result;
	}

  // Get content by content key in DB (generated unique hash for table for
  // better array traversal)
  public static function contentByKey( $contentkey ){
	  $result = DB::table('content')
      ->where('contentkey',$contentkey)
      ->limit(1)
      ->first();
	  return $result;
	}

  // Insert content
	public static function insertContent( $input ){
		$Corefunctions = new \App\customclasses\Corefunctions;
    $key = $Corefunctions->generateKey('10', 'content', 'contentkey');

		return DB::table('content')->insertGetId(array(
      'contentkey'    => $key,
		  'contentname'   => $input['cocktailname'],
      'description'   => $input['description'],
      'vimeovideoid'  => $input['videoid'],
      'hidden'        => ( isset( $input['hidden'] ) ) ? $input['hidden'] : '0',
		));
	}

  // Update content
	public static function updateContent( $input, $contentid ){
    return DB::table('content')->where('contentid', $contentid)->update(array(
	    'contentname'   => $input['contentname'],
      'description'   => $input['description'],
      'vimeovideoid'  => $input['videoid'],
      'hidden'        => ( isset( $input['hidden'] ) ) ? $input['hidden'] : '0',
	  ));
	}

  // Update content status (used for AJAX call)
  public static function updateContentStatus($contentid,$hidden){
  	DB::table('content')
      ->where('contentid', $contentid)
      ->update(array(
        'hidden' => $hidden
      ));
	}

  // Soft delete content from admin panel (used to remove item from showing up
  // in admin views while retaining it for contiguity/auditing)
  public static function softDeleteContent($contentid){
    DB::table('content')
      ->where('contentid',$contentid)
      ->update(array(
        'status' => '0'
      ));
  }

  // Hard delete content by actually dropping it from the DB
  // Not currently used
  public static function hardDeleteContent($contentid){
    DB::table('content')
      ->where('contentid',$contentid)
      ->delete();
  }

  // Get content for frontend or backend
  public static function getContent($environment){
    $hidden = ($environment == 'frontend') ? '0' : '1';
    $result = DB::table('content')
      ->where('hidden','0')
      ->where('status','1')
      ->get();
    return $result;
  }
}
