<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('book_id', 30)->nullable();
        });

        DB::table('books')
            ->whereNull('book_id')
            ->orderBy('id')
            ->chunkById(100, function ($books) {
                foreach ($books as $book) {
                    DB::table('books')
                        ->where('id', $book->id)
                        ->update(['book_id' => 'BOOK-'.$book->id]);
                }
            });

        Schema::table('books', function (Blueprint $table) {
            $table->string('book_id', 30)->nullable(false)->change();
            $table->unique('book_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['book_id']);
            $table->dropColumn('book_id');
        });
    }
};
