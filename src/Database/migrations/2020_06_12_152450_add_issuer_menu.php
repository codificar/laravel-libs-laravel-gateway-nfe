<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddIssuerMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::updateOrCreate(array('id' => 6103), 
        array('name' => 'admin_issuer', 'parent_id' => 2318, 'order' => 202, 'is_menu' => 1, 'url' => '/admin/issuer/company/create', 'icon' => 'mdi mdi-human-male'));

		if($permission){
			$profile1 = Profile::find(1);
			if($profile1)
				$profile1->permissions()->attach($permission->id);

			$profile4 = Profile::find(4);
			if($profile4)
				$profile4->permissions()->attach($permission->id);
			
		}
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
