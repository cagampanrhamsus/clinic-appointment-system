<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Laravel\Sanctum\HasApiTokens;

use App\Models\MedicalHistory;
use App\Models\Appointment;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Medical history (patient)
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class, 'patient_id');
    }

    // Appointments as patient
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // Appointments as doctor
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // Notifications
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }
}
