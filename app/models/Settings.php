<?php
require_once __DIR__ . "/../config/database.php";

class Settings
{


    public static function get()
    {
        global $pdo;
        return $pdo->query("SELECT * FROM site_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($site_name, $footer_text, $logo = null, $copyright_text = null, $logo_polinema = null, $logo_jti = null, $social_instagram = null, $social_facebook = null, $social_youtube = null)
    {
        global $pdo;
        $fields = "site_name = :site_name, footer_text = :footer_text, updated_at = NOW()";
        $params = ['site_name' => $site_name, 'footer_text' => $footer_text];
        if ($logo) {
            $fields .= ", logo = :logo";
            $params['logo'] = $logo;
        }
        if ($logo_polinema) {
            $fields .= ", logo_polinema = :logo_polinema";
            $params['logo_polinema'] = $logo_polinema;
        }
        if ($logo_jti) {
            $fields .= ", logo_jti = :logo_jti";
            $params['logo_jti'] = $logo_jti;
        }
        if ($copyright_text !== null) {
            $fields .= ", copyright_text = :copyright_text";
            $params['copyright_text'] = $copyright_text;
        }
        if ($social_instagram !== null) {
            $fields .= ", social_instagram = :social_instagram";
            $params['social_instagram'] = $social_instagram;
        }
        if ($social_facebook !== null) {
            $fields .= ", social_facebook = :social_facebook";
            $params['social_facebook'] = $social_facebook;
        }
        if ($social_youtube !== null) {
            $fields .= ", social_youtube = :social_youtube";
            $params['social_youtube'] = $social_youtube;
        }
        $stmt = $pdo->prepare("UPDATE site_settings SET $fields");
        return $stmt->execute($params);
    }
}
