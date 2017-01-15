<?php

namespace geertw\Yii2\Adsense;

use Yii;
use yii\helpers\Html;

class AdsenseWidget extends \yii\base\Widget {
    public $client;
    public $slot;
    public $enabled;
    public $responsive = true;
    public $visible = true;

    private $adSizes = [
        [728, 90]
    ];

    public function init() {
        parent::init();

        if (empty($this->client)) {
            // Try to load from params:
            if (isset(Yii::$app->params['adsense']) && isset(Yii::$app->params['adsense']['client'])) {
                $this->client = Yii::$app->params['adsense']['client'];
            }
        }
        if (empty($this->slot)) {
            if (isset(Yii::$app->params['adsense']) && isset(Yii::$app->params['adsense']['slot'])) {
                $this->slot = Yii::$app->params['adsense']['slot'];
            }
        }
        if (empty($this->enabled)) {
            if (isset(Yii::$app->params['adsense']) && isset(Yii::$app->params['adsense']['enabled'])) {
                $this->enabled = Yii::$app->params['adsense']['enabled'];
            }
        }

        if (empty($this->client) || empty($this->slot)) {
            $this->enabled = false;
        }
    }

    public function run() {
        if ($this->visible == false) {
            return "<!-- Adsense widget not visible -->";
        }

        if ($this->enabled == true) {
            $code = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
            $code .= "\n<ins class=\"adsbygoogle\"";
            $code .= ' style="display:block"';
            $code .= ' data-ad-client="' . Html::encode($this->client) . '"';
            $code .= ' data-ad-slot="' . Html::encode($this->slot) . '"';
            if ($this->responsive == true) {
                $code .= ' data-ad-format="auto"';
            }
            $code .= "></ins>\n";
            $code .= '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
        } else {
            $code = '<div class="adsense-disabled" style="background-color: #d50000; color: #fff">';
            $code .= "Adsense disabled";
            $code .= "</div>";
        }

        return $code;
    }
}
