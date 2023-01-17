<?php

namespace Prajwal89\LaravelShareTo;

class LaravelShareTo
{

    /**
     * Html to prefix before the share links
     *
     * @var string
     */
    protected $prefix = '<div id="laravel-share-this">';


    /**
     * Html to append after the share links
     *
     * @var string
     */
    protected $suffix = '</div>';


    /**
     * The share urls
     *
     * @var array
     */
    protected $shareUrls = [];


    /**
     * The generated html
     *
     * @var string
     */
    protected $html = '';


    protected $providerSettings = [
        'facebook' => [
            'uri' => 'https://www.facebook.com/sharer/sharer.php',
            'primaryColor' => '#4267B2',
        ],
        'whatsapp' => [
            'uri' => 'https://wa.me',
            'primaryColor' => '#075E54',
        ],
        'twitter' => [
            'uri' => 'https://twitter.com/intent/tweet',
            'primaryColor' => '#1DA1F2',
        ],
        'telegram' => [
            'uri' => 'https://telegram.me/share/url',
            'primaryColor' => '#0088cc',
        ],
        'email' => [
            'uri' => 'mailto:',
            'primaryColor' => '#808080',
        ],
    ];


    /**
     * @param string|null $title
     * @param string|null $url 
     * @param array $options
     * @return $this
     */

    function __construct(public string $title, public string $url = '', protected $options = [])
    {
        $this->options = array_replace(config('laravel-share-to.options') ?? [], $this->options);
    }

    /**
     * Generate links for all the providers.
     *
     * @return $this
     */

    public function all(): self
    {
        $this->facebook();
        $this->whatsapp();
        $this->twitter();
        $this->telegram();
        $this->email();
        return $this;
    }

    public function facebook(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['u' => $this->url, 'quote' => urlencode($this->title)]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function whatsapp(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "/?" . http_build_query(['text' =>  $this->title . "\n\n" . $this->url]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function twitter(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function telegram(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function email(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['subject' =>  $this->title, 'body' => $this->url], null, null, PHP_QUERY_RFC3986);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function getButtons(): string
    {
        $this->html .= $this->getContainerPrefix();

        foreach ($this->shareUrls as $provider => $url) {
            $this->html .= "<a href='$url' target='_blank' style='" . $this->getButtonInlineStyles($provider) . "'>" . ucfirst($provider) . "</a>";
        }

        $this->html .= $this->suffix;

        return $this->html;
    }

    private function getButtonInlineStyles($provider): string
    {
        $styles = [];
        $styles[] = 'text-decoration:none';
        $styles[] = 'color:' .  $this->providerSettings[$provider]['primaryColor'];
        $styles[] = 'padding:' . $this->options['paddingX'] . 'px ' . $this->options['paddingY'] . 'px';
        $styles[] = 'border:' . $this->options['borderWidth'] . 'px solid ' . $this->providerSettings[$provider]['primaryColor'];
        $styles[] = 'border-radius:' . $this->options['radius'] . 'px';
        return implode(';', $styles);
    }

    private function getContainerInlineStyles(): string
    {
        $styles = [];
        $styles[] = 'display:flex';
        $styles[] = 'flex-wrap:wrap';
        $styles[] = 'gap:' .  $this->options['buttonGap'] . 'px';
        $styles[] = 'justify-content:' .  $this->options['alignment'];
        return implode(';', $styles);
    }

    private function getContainerPrefix(): string
    {
        return '<div id="laravel-share-this" style="' . $this->getContainerInlineStyles() . '">';
    }


    public function getRawLinks(): array
    {
        $this->all();
        return $this->shareUrls;
    }

    public function only(array $providers)
    {
        foreach ($providers as $provider) {
            if (in_array($provider, array_keys($this->providerSettings))) {
                $this->{$provider}();
            }
        }
        return $this;
    }

    public function generateShareUrl($chanel, $urlToRedirect)
    {
        $payload = [
            'title' => $this->title,
            'chanel' => $chanel,
            'urlToRedirect' => $urlToRedirect,
            'urlToShare' => $this->url,
        ];

        if ($this->options['tracking']) {
            return config('laravel-share-to.trackingEndpoint') . '?payload=' . base64_encode(json_encode($payload));
        } else {
            return $urlToRedirect;
        }
    }
}
