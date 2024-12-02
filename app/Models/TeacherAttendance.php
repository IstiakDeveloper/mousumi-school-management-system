<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'date',
        'first_attendance',
        'last_attendance',
        'total_punches',
        'status',
        'attendance_logs'
    ];

    protected $casts = [
        'date' => 'date',
        'first_attendance' => 'datetime',
        'last_attendance' => 'datetime',
        'attendance_logs' => 'array'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public static function processAttendanceLog(array $log, Teacher $teacher): self
    {
        $timestamp = Carbon::parse($log['timestamp']);
        $date = $timestamp->toDateString();

        // Get or create attendance record for this date
        $attendance = self::firstOrNew([
            'teacher_id' => $teacher->id,
            'date' => $date,
        ]);

        // Get current logs or initialize empty array
        $currentLogs = $attendance->attendance_logs ?? [];

        // Add new log
        $currentLogs[] = $log;

        // Sort logs by timestamp
        usort($currentLogs, function ($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });

        // Update the model
        $attendance->fill([
            'attendance_logs' => $currentLogs,
            'first_attendance' => Carbon::parse($currentLogs[0]['timestamp']),
            'last_attendance' => Carbon::parse(end($currentLogs)['timestamp']),
            'total_punches' => count($currentLogs),
            'status' => self::determineStatus(Carbon::parse($currentLogs[0]['timestamp']))
        ]);

        // Save the record
        $attendance->save();

        return $attendance;
    }

    private static function determineStatus(Carbon $checkInTime): string
    {
        // Assuming school starts at 8:00 AM
        $schoolStartTime = Carbon::parse($checkInTime->format('Y-m-d') . ' 08:00:00');

        if ($checkInTime->gt($schoolStartTime->copy()->addMinutes(30))) {
            return 'late';
        }

        return 'present';
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->first_attendance || !$this->last_attendance) {
            return null;
        }

        $duration = $this->last_attendance->diff($this->first_attendance);
        return sprintf(
            '%d hours, %d minutes',
            $duration->h + ($duration->d * 24),
            $duration->i
        );
    }
    public static function markAbsentForDate(Carbon $date)
    {
        // Get all active teachers who don't have attendance for the given date
        $teachersWithoutAttendance = Teacher::where('job_status', 'active')
            ->whereDoesntHave('attendances', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })
            ->get();

        foreach ($teachersWithoutAttendance as $teacher) {
            self::create([
                'teacher_id' => $teacher->id,
                'date' => $date,
                'status' => 'absent',
                'total_punches' => 0,
                'attendance_logs' => []
            ]);
        }

        return $teachersWithoutAttendance->count();
    }
}
