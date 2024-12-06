<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function (Blueprint $table) {
			$table->id();
			$table->string('name', 255)->unique();
			$table->text('description')->nullable();
			$table->timestamps();
		});

		// Insert common roles
		DB::table('roles')->insert([
			[
				'name' => 'Admin',
				'description' => 'Administrator with full access to all features.',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Editor',
				'description' => 'Can edit and manage content.',
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Viewer',
				'description' => 'Can only view content.',
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('roles');
	}
};
