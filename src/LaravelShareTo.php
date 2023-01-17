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
     * Selected providers for processing
     *
     * @var array
     */
    protected $chosenProviders = [];

    /**
     * @param string|null $title
     * @param string|null $urlToShare 
     * @param array $options
     * @return $this
     */
    function __construct(public string $title, public string $urlToShare = '', protected $options = [])
    {
        if (empty($this->urlToShare)) {
            $this->urlToShare = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        
        $this->options = array_replace(config('laravel-share-to.options') ?? [], $this->options);
    }

    /**
     * Generate links for all the providers.
     *
     * @return $this
     */
    public function all(): self
    {
        array_map(function ($provider) {
            $this->{$provider}();
        }, array_keys($this->providerSettings));

        return $this;
    }

    /**
     * Generate facebook share url
     *
     * @return self
     */
    public function facebook(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['u' => $this->urlToShare, 'quote' => urlencode($this->title)]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    /**
     * Generate whatsapp share url
     *
     * @return self
     */
    public function whatsapp(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "/?" . http_build_query(['text' =>  $this->title . "\n\n" . $this->urlToShare]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    /**
     * Generate twitter share url
     *
     * @return self
     */
    public function twitter(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->urlToShare]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    /**
     * Generate telegram share url
     *
     * @return self
     */
    public function telegram(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->urlToShare]);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    /**
     * Generate email share url
     *
     * @return self
     */
    public function email(): self
    {
        $urlToRedirect = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['subject' =>  $this->title, 'body' => $this->urlToShare], null, null, PHP_QUERY_RFC3986);

        $url = $this->generateShareUrl(__FUNCTION__, $urlToRedirect);

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    /**
     * Generate html for social buttons
     *
     * @return string
     */
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
        if (empty($this->chosenProviders)) {
            $this->all();
        } else {
            array_map(function ($provider) {
                $this->{$provider}();
            }, $this->chosenProviders);
        }

        return $this->shareUrls;
    }

    /**
     * Call only selected providers
     *
     * @param array $providers
     * @return self
     */

    public function only(array $providers): self
    {
        foreach ($providers as $provider) {
            if (in_array($provider, array_keys($this->providerSettings))) {
                $this->{$provider}(); //call method e.g $this->facebook()
                $this->chosenProviders[] = $provider;
            }
        }

        return $this;
    }


    public function generateShareUrl(string $chanel, string $urlToRedirect): string
    {
        $payload = [
            'title' => $this->title,
            'chanel' => $chanel,
            'urlToRedirect' => $urlToRedirect,
            'urlToShare' => $this->urlToShare,
        ];

        return $this->options['tracking'] ?  config('laravel-share-to.trackingEndpoint') . '?payload=' . base64_encode(json_encode($payload)) : $urlToRedirect;
    }
}
