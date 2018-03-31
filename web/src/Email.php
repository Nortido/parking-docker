<?php
/**
 * @author Evgeny Novoselov <nortido@gmail.com>
 */

namespace App;


class Email
{
    /**
     * @param   string $email
     * @return  bool
     */
    static function email_validate( string $email ) : bool
    {
        # Email pattern parts
        $FIRSTPART  = "[_a-z0-9-]{3,}+";
        $DOTPART   = "(\.[_a-z0-9-]+)*";
        $DOMAIN     = "[a-z0-9-]+(\.[a-z0-9-]+)*";
        $ZONE       = "(\.[a-z]{2,})";

        # Email pattern
        $pattern = "/^" . $FIRSTPART . $DOTPART . "@" . $DOMAIN . $ZONE . "$/i";

        return preg_match( $pattern, $email ) > 0;
    }

    /**
     * @param   string $email
     * @return  string
     */
    static function get_domain( string $email ) : string
    {
        if ( !self::email_validate( $email ) ) {
            # Group invalid domains
            return "INVALID";
        }

        return explode("@", $email )[1];
    }

}