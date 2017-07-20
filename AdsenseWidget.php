<?php

namespace geertw\Yii2\Adsense;

use Yii;
use yii\helpers\Html;

/**
 * AdSense widget. This widget renders the Google AdSense code for showing AdSense banners.
 *
 * @package geertw\Yii2\Adsense
 */
class AdsenseWidget extends \yii\base\Widget {
    /**
     * @var string AdSense client ID
     */
    public $client;

    /**
     * @var string AdSense slot ID
     */
    public $slot;

    /**
     * @var bool Whether AdSense is enabled or not
     */
    public $enabled;

    /**
     * @var bool Whether this banner is responsive
     */
    public $responsive = true;

    /**
     * @var bool Whether this widget is visible at all
     */
    public $visible = true;

    /**
     * List of all ad sizes Adsense has to offer (including regional formats).
     * They are used to show whether a responsive block has a valid size.
     * @var array
     */
    private $adSizes = [
        [300, 250],
        [336, 280],
        [728, 90],
        [300, 600],
        [320, 100],
        [320, 50],
        [468, 60],
        [234, 60],
        [120, 600],
        [120, 240],
        [160, 600],
        [300, 1050],
        [970, 90],
        [970, 250],
        [250, 250],
        [200, 200],
        [180, 150],
        [125, 125],
        [240, 400],
        [980, 120],
        [250, 360],
        [930, 180],
        [580, 400],
        [750, 300],
        [750, 200],
        [750, 100],
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

    /**
     * Render the widget
     * @return string
     */
    public function run() {
        if ($this->visible == false) {
            return "<!-- Adsense widget not visible -->";
        }

        if ($this->enabled == true) {
            // Return Google AdSense code
            $this->view->registerJsFile('//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js', ['async' => 'async']);
            $code = "\n<ins class=\"adsbygoogle\"";
            $code .= ' style="display:block"';
            $code .= ' data-ad-client="' . Html::encode($this->client) . '"';
            $code .= ' data-ad-slot="' . Html::encode($this->slot) . '"';
            if ($this->responsive == true) {
                $code .= ' data-ad-format="auto"';
            }
            $code .= "></ins>\n";
            $code .= '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
        } else {
            // AdSense can or should not be shown. Return dummy banner.
            $id = uniqid('disabled_adsense_');
            $code = '<ins class="adsbygoogle disabled" id="' . $id . '" style="background-color: #d4e926; color: #000; display: block; text-align: center; text-decoration: none">';
            $code .= "Adsense disabled";
            $code .= "</ins>\n";

            if ($this->responsive == true) {
                $adSizes = json_encode($this->adSizes);

                $code .= <<<JSCODE
<script>
    var disabled_banner = document.getElementById('{$id}');
    var width = disabled_banner.clientWidth;
    var height = disabled_banner.clientHeight;
    var sizes = {$adSizes};
    var validSize = false;
    for (index = 0; index < sizes.length; ++index) {
        if (sizes[index][0] == width && sizes[index][1] == height) {
            disabled_banner.innerHTML = 'Adsense disabled<br><strong>' + width + 'x' + height + '</strong>';
            validSize = true;
            break;
        }
    }
    if (validSize == false) {
        disabled_banner.innerHTML = 'Adsense disabled<br><strong>Invalid size!</strong> (' + width + 'x' + height + ')';
        disabled_banner.style.color = '#ffffff';
        disabled_banner.style.backgroundColor = '#d50000';
    }
</script>
JSCODE;
            }
        }

        return $code;
    }
}
