<?php

use App\Enums\StatusEnum;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('identifier');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('short_codes')->nullable();
            $table->string('email_type')->nullable();
            $table->enum('status', [
                StatusEnum::ACTIVE->value,
                StatusEnum::INACTIVE->value,
            ])->default(StatusEnum::ACTIVE->value);
            $table->timestamps();
        });



        $now = now();

        $data = [
            [
                'subject'       => 'Email Confirmation',
                'identifier'    => 'Email Confirmation',
                'title'         => 'email_confirmation',
                'short_codes'   => '{name},{email},{site_name},{confirmation_link}',
                'body'          => '<p>Hi {name},</p><p>Please confirm your email by clicking the link below:</p><p>{confirmation_link}</p><p><br></p><p>Thanks</p><p>{site_name}</p>',
                'email_type'    => 'authentication',
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            [
                'subject'       => 'Welcome Email',
                'identifier'    => 'Welcome Email',
                'title'         => 'welcome_email',
                'short_codes'   => '{site_name}',
                'body'          => 'Welcome! We are excited you are here.',
                'email_type'    => 'authentication',
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            [
                'subject'       => 'Password Reset Mail',
                'identifier'    => 'Password Reset Mail',
                'title'         => 'password_reset',
                'short_codes'   => '{site_name}',
                'body'          => 'We have received a request to reset your password. To proceed with the password reset, please click on the link below:',
                'email_type'    => 'authentication',
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            [
                'subject'       => 'Recovery Successful Mail',
                'identifier'    => 'Recovery Successful Mail',
                'title'         => 'recovery_mail',
                'short_codes'   => '{site_name}',
                'body'          => 'Your password has been successfully reset. You can now log in to your account using your new password.If you have any issues or questions, please do not hesitate to contact our support team.Thank you,',
                'email_type'    => 'authentication',
                'created_at'    => $now,
                'updated_at'    => $now
            ],
        ];

        EmailTemplate::insert($data);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
};
