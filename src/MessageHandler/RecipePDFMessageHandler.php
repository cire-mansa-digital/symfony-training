<?php

namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\Process\Process;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{

    public function __construct(
        #[Autowire('%kernel.project_dir%/public/PDF')]
        private readonly string $path,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}
    public function __invoke(RecipePDFMessage $message): void
    {
        $url = $this->urlGenerator->generate(
            'recipe.show',
            ['id' => $message->id, 'slug' => $message->slug],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $targetUrl = str_replace(['127.0.0.1', 'localhost'], 'host.docker.internal', $url);
        $process = new Process([
            'curl', 
            '--request',
            'POST',
            'http://localhost:3000/forms/chromium/convert/url',
            '--form',
            'url=' . $targetUrl,
            '-o',
            $this->path . '/' . $message->id . '.pdf',
        ]);
        //    file_put_contents($this->path. '/'. $message->id. '.pdf', 'My Content');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
