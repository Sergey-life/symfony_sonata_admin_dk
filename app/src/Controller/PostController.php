<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('post/{id}', name: 'post_show')]
    public function show(int $id, PostRepository $postRepository): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $postRepository->find($id),
        ]);
    }

    #[Route('post-send-email/{id}', name: 'post_send_email')]
    public function sendEmail(int $id, PostRepository $postRepository, MailerInterface $mailer)
    {
        $post = $postRepository->find($id);
    # Generate pdf from html
        $tmp = $this->getParameter('kernel.project_dir').'/public/tmp';
        $dompdf = new Dompdf([
            'logOutputFile' => '',
            'isRemoteEnabled' => true,
            'fontDir' => $tmp,
            'fontCache' => $tmp,
            'tempDir' => $tmp,
            'chroot' => $tmp,
        ]);
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Pacifico');
        $html = $this->renderView('post/show.html.twig', [
            'post' => $postRepository->find($id),
            'imageSrc' => $this->imageToBase64($this->getParameter('images_directory').$post->getImage())
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $output = $dompdf->output();

        $pdfFilePath = $this->getParameter('pdf_directory').'post.pdf';
        file_put_contents($pdfFilePath, $output);

        #Send pdf on email
        $email = (new Email())
            ->from('serhii.kharchenko333@gmail.com')
            ->to('strongpati215@gmail.com')
            ->subject('Welcome')
            ->text('Hello2!')
            ->attachFromPath($pdfFilePath, 'post.pdf', 'application/pdf');

        $mailer->send($email);

        return $this->redirectToRoute('post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }

    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);

        return $base64;
    }
}
