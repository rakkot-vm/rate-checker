<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Entity\ExchangeRateThreshold;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    public function __construct(
        private string $mailTo,
        private MailerInterface $mailer
    ) {
    }

    /**
     * @param ExchangeRateThreshold[] $reachedThresholds
     *
     * @throws TransportExceptionInterface
     */
    public function sendExchangeRateThresholdsReachedEmail(array $reachedThresholds): void
    {
        $email = (new TemplatedEmail())
            ->from('exchange@checker.com')
            ->to($this->mailTo)
            ->subject('Exchange rate thresholds reached!')
            ->context([
                'thresholds' => $reachedThresholds,
            ])
            ->htmlTemplate('email/exchange_rate_thresholds_reached.html.twig');

        $this->mailer->send($email);
    }
}
