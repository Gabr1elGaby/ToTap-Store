<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. cv_templates
        Schema::create('cv_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 15, 2)->default(5000);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 2. cvs
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('cv_templates');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('website')->nullable();
            $table->string('photo')->nullable();
            $table->string('job_title')->nullable();
            $table->text('profile')->nullable();
            $table->string('status')->default('PENDING'); // PENDING or PAID
            $table->timestamps();
        });

        // 3. cv_educations
        Schema::create('cv_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('institution');
            $table->string('major')->nullable();
            $table->string('degree')->nullable();
            $table->string('start_year')->nullable();
            $table->string('end_year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. cv_experiences
        Schema::create('cv_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('company');
            $table->string('position');
            $table->string('location')->nullable();
            $table->string('start_year')->nullable();
            $table->string('end_year')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. cv_organizations
        Schema::create('cv_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('organization_name');
            $table->string('role');
            $table->string('period')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 6. cv_internships
        Schema::create('cv_internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('company');
            $table->string('position');
            $table->string('period')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. cv_skills
        Schema::create('cv_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // 8. cv_certificates
        Schema::create('cv_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('name');
            $table->string('publisher')->nullable();
            $table->string('year')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });

        // 9. cv_projects
        Schema::create('cv_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('technologies')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });

        // 10. cv_languages
        Schema::create('cv_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->string('language');
            $table->string('proficiency')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_languages');
        Schema::dropIfExists('cv_projects');
        Schema::dropIfExists('cv_certificates');
        Schema::dropIfExists('cv_skills');
        Schema::dropIfExists('cv_internships');
        Schema::dropIfExists('cv_organizations');
        Schema::dropIfExists('cv_experiences');
        Schema::dropIfExists('cv_educations');
        Schema::dropIfExists('cvs');
        Schema::dropIfExists('cv_templates');
    }
};
