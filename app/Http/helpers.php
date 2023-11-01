<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Builder;
use App\Models\AccessList;
use App\Models\AccessListRole;

// check user bisa akses page/request
if (!function_exists('can_access')) {
	function can_access($menu_url)
	{
		try {
			// cari list roles yg dimiliki user
			$role = Auth::user()->role_users->where('active', '=', '1')->pluck('id');
			// cari menu yang akan di akses apakah ada di role access list
			$data = AccessList::whereHas('roles', function (Builder $query) use ($role) {
				$query->whereIn('role_id', $role);
			})->where('link', '=', $menu_url)->firstOrFail();
			return true;
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			abort(403);
		} catch (Exception $e) {
			abort(500);
		}
	}
}

// ambil list page yang bisa di akses user
if (!function_exists('get_list_menu')) {
	function get_list_menu($parent=0, $list_access = [])
	{
		// List dari menu dimulai dari parent nya 0
		$data = AccessList::where('type', '=', 'page')->where('parent', '=', $parent)->where('active', '=', '1')->whereIn('id', $list_access)->orderBy('order')->select('id', 'name', 'link', 'child', 'icon', 'parent')->get();

		// looping di data buat cari child nya
		foreach ($data as $record) {
			// check ada child menu tidak
			if ($record->child == '1') {
				// rekursif panggil fungsi nya untuk bikin child menu
				$record->child_menu = get_list_menu($record->id, $list_access);
			}
		}

		return $data;
	}
}

// ambil list akses (page/request) yang dimiliki user
if (!function_exists('get_list_access')) {
	function get_list_access($list_roles = [])
	{
		// List dari menu dimulai dari parent nya 0
		$data = AccessListRole::whereIn('role_id', $list_roles)->groupBy('access_list_id')->pluck('access_list_id');

		return $data;
	}
}

// ambil list request yang dimiliki user
if (!function_exists('get_list_request')) {
	function get_list_request($value='')
	{
		// code...
	}
}

// display menu di blade
if (!function_exists('show_menu')) {
	function show_menu($data, $path)
	{
		$htmlBlade = '';
		foreach ($data as $record) {
			if ($record->parent == 0) {
				if (is_null($record->child_menu)) {
					$htmlBlade .= "<li class='side-nav-item ".($record->link == $path ? 'menuitem-active' : '')."'>
	          <a href='".url($record->link)."' class='side-nav-link'>
	            <i class='".$record->icon."'></i>
	            <span> ".$record->name." </span>
	          </a>
	        </li>";
				} else {
					$htmlBlade .= "<li class='side-nav-item'>
	            <a data-bs-toggle='collapse' href='#sidebar".$record->id."' aria-expanded='false' aria-controls='sidebar".$record->id."' class='side-nav-link'>
	                <i class='".$record->icon."'></i>
	                <span> ".$record->name." </span>
	                <span class='menu-arrow'></span>
	            </a>
	            <div class='collapse' id='sidebar".$record->id."'>
	                <ul class='side-nav-second-level'>";
	        	
	        $htmlBlade .= show_menu($record->child_menu, $path);

					$htmlBlade .= "</ul>
	                            </div>
	                        </li>";
				}
			} else {
				$htmlBlade .= "<li class='".($record->link == $path ? 'menuitem-active' : '')."'>
                          <a href='".url($record->link)."' class='".($record->link == $path ? 'active' : '')."'>".$record->name."</a>
                      </li>";
			}

		}
		return $htmlBlade;
	}
}

// Show menu alternate untuk topnav
if (!function_exists('show_menu_alt')) {
	function show_menu_alt($data, $path)
	{
		$htmlBlade = '';
		foreach ($data as $record) {
			
			if (is_null($record->child_menu)) {
				// Jika ga punya anak
				$htmlBlade .= "<li class='nav-item dropdown'>
              <a href='".url($record->link)."' class='nav-link ".($record->link == $path ? 'active' : '')."'>
                <i class='".$record->icon." me-1'></i>".$record->name."
              </a>
            </li>";
			} else {
				// jika punya anakan
				$htmlBlade .= "<li class='nav-item dropdown'>
              <a class='nav-link dropdown-toggle arrow-none' href='".url($record->link)."' id='multiLevel".$record->id."' role='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class='".$record->icon." me-1'></i>".$record->name." <div class='arrow-down'></div>
              </a>
              <div class='dropdown-menu' aria-labelledby='multiLevel".$record->id."'>";

        $htmlBlade .= navbar_topnav_child($record->child_menu, $path);
                  
				$htmlBlade .= "</div>
            </li>";
			}
		}
		return $htmlBlade;
	}
}

// untuk handle children topnav
if (!function_exists('navbar_topnav_child')) {
	function navbar_topnav_child($data, $path)
	{
		$htmlBlade = '';

		foreach ($data as $record) {
			$htmlBlade .= "<a href='".url($record->link)."' class='dropdown-item ".($record->link == $path ? 'active' : '')."'>".$record->name."</a>";
			if (!is_null($record->child_menu)) {
				$htmlBlade .= navbar_topnav_child($record->child_menu, $path);
			}
		}

		return $htmlBlade;
	}
}

// ambil link pertama untuk user
if (!function_exists('get_first_page')) {
	function get_first_page()
	{
		$role = Auth::user()->role_users->pluck('id');
		try {
			$data = AccessList::whereHas('roles', function (Builder $query) use ($role) {
				$query->whereIn('role_id', $role);
			})->where('link', '!=', '#')->where('type', '=', 'page')->orderBy('order', 'asc')->firstOrFail();
			return $data->link;
		} catch (Exception $e) {
			abort(500);
		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			abort(400, "User doesn't have any authorization to access anything.");
		}
	}
}