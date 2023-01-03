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
        ]
    ];


    /**
     * The share urls
     *
     * @var array
     */
    protected $defaultOptions = [

        'tracking' => false,

        //options for container
        'buttonGap' => 10, //in px
        'alignment' => 'center', // accepts (start|center|end) alignment of of buttons in container

        //options for button
        'borderWidth' => 2,
        'radius' => 4,
        'paddingX' => 4,
        'paddingY' => 8,
    ];


    /**
     * @param string|null $title
     * @param string|null $url 
     * @param array $options
     * @return $this
     */

    function __construct(public string $title, public string $url = '', protected $options = [])
    {
        $this->options = array_replace($this->defaultOptions, $this->options);
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
        return $this;
    }

    public function facebook(): self
    {
        if ($this->options['tracking']) {
            $payload = [
                'title' => $this->title,
                'chanel' => __FUNCTION__,
                'urlToRedirect' => $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['u' => $this->url, 'quote' => urlencode($this->title)]),
                'urlRedirectingFrom' => $this->url,
            ];
            $url = $this->generatePayloadUrl($payload);
        } else {
            $url = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['u' => $this->url, 'quote' => urlencode($this->title)]);
        }

        $this->shareUrls[__FUNCTION__] = $url;
        return $this;
    }

    public function whatsapp(): self
    {
        if ($this->options['tracking']) {
            $payload = [
                'title' => $this->title,
                'chanel' => __FUNCTION__,
                'urlToRedirect' => $this->providerSettings[__FUNCTION__]['uri'] . "/?" . http_build_query(['text' =>  $this->title . "\n\n" . $this->url]),
                'urlRedirectingFrom' => $this->url,
            ];
            $url = $this->generatePayloadUrl($payload);
        } else {
            $url = $this->providerSettings[__FUNCTION__]['uri'] . "/?" . http_build_query(['text' =>  $this->title . "\n\n" . $this->url]);
        }

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function twitter(): self
    {
        if ($this->options['tracking']) {
            $payload = [
                'title' => $this->title,
                'chanel' => __FUNCTION__,
                'urlToRedirect' => $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]),
                'urlRedirectingFrom' => $this->url,
            ];
            $url = $this->generatePayloadUrl($payload);
        } else {
            $url = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]);
        }

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function telegram(): self
    {
        if ($this->options['tracking']) {
            $payload = [
                'title' => $this->title,
                'chanel' => __FUNCTION__,
                'urlToRedirect' => $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]),
                'urlRedirectingFrom' => $this->url,
            ];
            $url = $this->generatePayloadUrl($payload);
        } else {
            $url = $this->providerSettings[__FUNCTION__]['uri'] . "?" . http_build_query(['text' =>  $this->title . "\n", 'url' => $this->url]);
        }

        $this->shareUrls[__FUNCTION__] = $url;

        return $this;
    }

    public function getButtons($options = []): string
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

    public function generatePayloadUrl($payload)
    {
        return config('laravel-share-to.trackingEndpoint') . '?payload=' . base64_encode(json_encode($payload));
    }
}
