<?php

namespace Application\Service;

use Domain\Repository\EmailTemplateRepositoryInterface;
use Domain\Repository\EmailLogRepositoryInterface;
use PHPMailer\PHPMailer\PHPMailer;
use Domain\Model\EmailLog;

class EmailNotificationService
{
    private $templateRepository;
    private $logRepository;

    public function __construct(
        EmailTemplateRepositoryInterface $templateRepository,
        EmailLogRepositoryInterface $logRepository
    ) {
        $this->templateRepository = $templateRepository;
        $this->logRepository = $logRepository;
    }

    public function sendByTrigger(string $trigger, array $variables, $userId, string $role): void
    {
        $template = $this->templateRepository->findByCode($trigger);
        if (!$template || !$template->isActive()) {
            return;
        }

        $subject = $this->render($template->getSubject(), $variables);
        $body = $this->render($template->getBody(), $variables);

        $mail = new PHPMailer(true);
        $mail->setFrom('noreply@example.com');
        $mail->addAddress($variables['email'] ?? '');
        $mail->Subject = $subject;
        $mail->msgHTML($body);

        $status = 'sent';
        $error = null;
        try {
            $mail->send();
        } catch (\Exception $e) {
            $status = 'error';
            $error = $e->getMessage();
        }

        $log = new EmailLog(null, $trigger, $userId, $role, $status, $variables, $error, new \DateTime());
        $this->logRepository->save($log);
    }

    private function render(string $content, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $content = str_replace('{{'.$key.'}}', $value, $content);
        }
        return $content;
    }
}
