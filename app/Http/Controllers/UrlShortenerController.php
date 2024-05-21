<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\UrlShortener;

class UrlShortenerController extends Controller
{
    public function index()
    {
        $urls = UrlShortener::all();
        return view('welcome', compact('urls'));
    }

    public function shorten(Request $request)
    {
        // Gelen veriyi doğrulama kuralları
        $rules = [
            'url' => 'required|url',
        ];

        // Veri doğrulamasını gerçekleştir
        $validator = Validator::make($request->all(), $rules);

        // Eğer doğrulama başarısızsa, hata mesajları ile birlikte ana sayfaya dön
        if ($validator->fails()) {
            return redirect('/')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Veritabanında daha önce kısaltılmış bir URL var mı kontrol et
        $existingUrl = UrlShortener::where('original_url', $request->url)->first();

        // Eğer varsa, daha önceki kısaltılmış URL'i kullanıcıya göster
        if ($existingUrl) {
            return redirect('/')
                        ->with('shortened', $existingUrl->shortened_url);
        }

        // Kısaltma işlemi
        $shortenedUrl = $this->generateShortenedUrl();

        // Kısaltılmış URL'i veritabanında kontrol et
        $existingShortenedUrl = UrlShortener::where('shortened_url', $shortenedUrl)->first();

        // Eğer kısaltılmış URL, başka bir orijinal URL ile ilişkilendirilmişse, farklı bir kısaltılmış URL oluştur
        if ($existingShortenedUrl) {
            $shortenedUrl = $this->generateShortenedUrl();
        }

        // Kısaltılmış URL'i veritabanına kaydet
        $urlShortener = new UrlShortener();
        $urlShortener->original_url = $request->url;
        $urlShortener->shortened_url = $shortenedUrl;
        $urlShortener->save();

        // Kullanıcıya kısaltılmış URL'i göster
        return redirect('/')
                    ->with('shortened', $shortenedUrl);
    }

    private function generateShortenedUrl()
    {
        // Kısaltılmış URL'i oluştur
        $shortenedUrl = Str::random(12);

        // Veritabanında kontrol et ve benzersiz olana kadar yeni bir kısaltma oluştur
        while (UrlShortener::where('shortened_url', $shortenedUrl)->exists()) {
            $shortenedUrl = Str::random(12);
        }

        return $shortenedUrl;
    }
}
