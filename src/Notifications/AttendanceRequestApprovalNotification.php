<?php

namespace Hito\Modules\Attendance\Notifications;

use Hito\Platform\Contracts\ViewNotification;
use Hito\Platform\DTO\NotificationDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Hito\Modules\Attendance\Models\AttendanceRequest;
use Hito\Modules\Attendance\Services\AttendanceRequestService;

class AttendanceRequestApprovalNotification extends Notification implements ShouldQueue, ViewNotification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public AttendanceRequest $attendanceRequest)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Leave request waiting for approval')
            ->line('An employee awaits for your approval on a leave request.')
            ->line('Please click the button below to go on the attendance approval page.')
            ->action('Approval Page', $this->generateApprovalPageUrl())
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->attendanceRequest->id
        ];
    }

    public static function toView(DatabaseNotification $notification): ?NotificationDTO
    {
        if (empty($notification->data['id'])) {
            return null;
        }

        try {
            $model = app(AttendanceRequestService::class)->getById($notification->data['id']);
        } catch (ModelNotFoundException) {
            return null;
        }

        return new NotificationDTO('Attendance request awaiting approval',
            "<b>{$model->user?->name} {$model->user?->surname}</b> has created an attendance request for <b>{$model->type?->name}</b> and is waiting for your approval.", 'Open',
            route('attendance.requests.show', $model->id));
    }

    private function generateApprovalPageUrl(): string
    {
        return URL::route('attendance.requests.show', ['attendance_request' => $this->attendanceRequest->id]);
    }
}
