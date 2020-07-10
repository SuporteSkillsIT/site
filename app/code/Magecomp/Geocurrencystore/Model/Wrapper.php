<?php
namespace Magecomp\Geocurrencystore\Model;

class Wrapper
{
    private $flags;
    private $filehandle;
    private $memory_buffer;
    private $databaseType;
    private $databaseSegments;
    private $record_length;
    private $shmid;

    private $GEOIP_COUNTRY_CODES = array(
        "", "AP", "EU", "AD", "AE", "AF", "AG", "AI", "AL", "AM", "CW",
        "AO", "AQ", "AR", "AS", "AT", "AU", "AW", "AZ", "BA", "BB",
        "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BM", "BN", "BO",
        "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD",
        "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "CR",
        "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO",
        "DZ", "EC", "EE", "EG", "EH", "ER", "ES", "ET", "FI", "FJ",
        "FK", "FM", "FO", "FR", "SX", "GA", "GB", "GD", "GE", "GF",
        "GH", "GI", "GL", "GM", "GN", "GP", "GQ", "GR", "GS", "GT",
        "GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID",
        "IE", "IL", "IN", "IO", "IQ", "IR", "IS", "IT", "JM", "JO",
        "JP", "KE", "KG", "KH", "KI", "KM", "KN", "KP", "KR", "KW",
        "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS", "LT",
        "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MK", "ML",
        "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MV",
        "MW", "MX", "MY", "MZ", "NA", "NC", "NE", "NF", "NG", "NI",
        "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "PA", "PE", "PF",
        "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PS", "PT", "PW",
        "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD",
        "SE", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO",
        "SR", "ST", "SV", "SY", "SZ", "TC", "TD", "TF", "TG", "TH",
        "TJ", "TK", "TM", "TN", "TO", "TL", "TR", "TT", "TV", "TW",
        "TZ", "UA", "UG", "UM", "US", "UY", "UZ", "VA", "VC", "VE",
        "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "RS", "ZA",
        "ZM", "ME", "ZW", "A1", "A2", "O1", "AX", "GG", "IM", "JE",
        "BL", "MF", "BQ");

    private $GEOIP_COUNTRY_BEGIN = 16776960;
    private $GEOIP_STATE_BEGIN_REV0 = 16700000;
    private $GEOIP_STATE_BEGIN_REV1 = 16000000;
    private $GEOIP_MEMORY_CACHE = 1;
    private $GEOIP_SHARED_MEMORY = 2;
    private $STRUCTURE_INFO_MAX_SIZE = 20;
    private $GEOIP_COUNTRY_EDITION = 106;
    private $GEOIP_PROXY_EDITION = 8;
    private $GEOIP_ASNUM_EDITION = 9;
    private $GEOIP_NETSPEED_EDITION = 10;
    private $GEOIP_REGION_EDITION_REV0 = 112;
    private $GEOIP_REGION_EDITION_REV1 = 3;
    private $GEOIP_CITY_EDITION_REV0 = 111;
    private $GEOIP_CITY_EDITION_REV1 = 2;
    private $GEOIP_ORG_EDITION = 110;
    private $GEOIP_ISP_EDITION = 4;
    private $SEGMENT_RECORD_LENGTH = 3;
    private $STANDARD_RECORD_LENGTH = 3;
    private $ORG_RECORD_LENGTH = 4;
    private $GEOIP_SHM_KEY = 0x4f415401;
    private $GEOIP_DOMAIN_EDITION = 11;
    private $GEOIP_COUNTRY_EDITION_V6 = 12;
    private $GEOIP_LOCATIONA_EDITION = 13;
    private $GEOIP_ACCURACYRADIUS_EDITION = 14;
    private $GEOIP_CITY_EDITION_REV1_V6 = 30;
    private $GEOIP_CITY_EDITION_REV0_V6 = 31;
    private $GEOIP_NETSPEED_EDITION_REV1 = 32;
    private $GEOIP_NETSPEED_EDITION_REV1_V6 = 33;
    private $GEOIP_USERTYPE_EDITION = 28;
    private $GEOIP_USERTYPE_EDITION_V6 = 29;
    private $GEOIP_ASNUM_EDITION_V6 = 21;
    private $GEOIP_ISP_EDITION_V6 = 22;
    private $GEOIP_ORG_EDITION_V6 = 23;
    private $GEOIP_DOMAIN_EDITION_V6 = 24;

    /**
     * @param $filename
     * @param $flags
     * @return bool
     */
    public function geoip_open($filename, $flags)
    {
        /** @var TYPE_NAME $this */
        $this->flags = $flags;
        if ($this->flags & $this->GEOIP_SHARED_MEMORY) {
            $this->shmid = @shmop_open($this->GEOIP_SHM_KEY, "a", 0, 0);
        } else {
            if (file_exists($filename) && $this->filehandle = fopen($filename, "rb")) {
                if ($this->flags & $this->GEOIP_MEMORY_CACHE) {
                    $s_array = fstat($this->filehandle);

                    $this->memory_buffer = fread($this->filehandle, $s_array['size']);
                }
            } else {
                return false;
            }
        }

        $this->_setup_segments();
        return true;
    }

    public function geoip_close()
    {
        if ($this->flags & $this->GEOIP_SHARED_MEMORY) {
            return true;
        }

        return fclose($this->filehandle);
    }

    public function geoip_country_code_by_addr($addr)
    {
        $country_id = $this->geoip_country_id_by_addr($addr);
        return $country_id !== false ? $this->GEOIP_COUNTRY_CODES[$country_id] : false;
    }

    public function geoip_country_id_by_addr($addr)
    {
        $ipnum = ip2long($addr);
        return $this->_geoip_seek_country($ipnum) - $this->GEOIP_COUNTRY_BEGIN;
    }

    private function _geoip_seek_country($ipnum)
    {
        $offset = 0;
        for ($depth = 31; $depth >= 0; --$depth) {
            if ($this->flags & $this->GEOIP_MEMORY_CACHE) {
                // workaround php's broken substr, strpos, etc handling with
                // mbstring.func_overload and mbstring.internal_encoding
                $enc = mb_internal_encoding();
                mb_internal_encoding('ISO-8859-1');

                $buf = substr($this->memory_buffer,
                    2 * $this->record_length * $offset,
                    2 * $this->record_length);

                mb_internal_encoding($enc);
            } elseif ($this->flags & $this->GEOIP_SHARED_MEMORY) {
                $buf = @shmop_read($this->shmid,
                    2 * $this->record_length * $offset,
                    2 * $this->record_length);
            } else {
                fseek($this->filehandle, 2 * $this->record_length * $offset, SEEK_SET) == 0
                or trigger_error("fseek failed");
                $buf = fread($this->filehandle, 2 * $this->record_length);
            }
            $x = array(0, 0);
            for ($i = 0; $i < 2; ++$i) {
                for ($j = 0; $j < $this->record_length; ++$j) {
                    $x[$i] += ord($buf[$this->record_length * $i + $j]) << ($j * 8);
                }
            }
            if ($ipnum & (1 << $depth)) {
                if ($x[1] >= $this->databaseSegments) {
                    return $x[1];
                }
                $offset = $x[1];
            } else {
                if ($x[0] >= $this->databaseSegments) {
                    return $x[0];
                }
                $offset = $x[0];
            }
        }
        trigger_error("error traversing database - perhaps it is corrupt?", E_USER_ERROR);
        return false;
    }

    private function _setup_segments()
    {
        $this->databaseType = $this->GEOIP_COUNTRY_EDITION;
        $this->record_length = $this->STANDARD_RECORD_LENGTH;
        if ($this->flags & $this->GEOIP_SHARED_MEMORY) {
            $offset = @shmop_size($this->shmid) - 3;
            for ($i = 0; $i < $this->STRUCTURE_INFO_MAX_SIZE; $i++) {
                $delim = @shmop_read($this->shmid, $offset, 3);
                $offset += 3;
                if ($delim == (chr(255) . chr(255) . chr(255))) {
                    $this->databaseType = ord(@shmop_read($this->shmid, $offset, 1));
                    $offset++;

                    if ($this->databaseType == $this->GEOIP_REGION_EDITION_REV0) {
                        $this->databaseSegments = $this->GEOIP_STATE_BEGIN_REV0;
                    } else if ($this->databaseType == $this->GEOIP_REGION_EDITION_REV1) {
                        $this->databaseSegments = $this->GEOIP_STATE_BEGIN_REV1;
                    } else if (($this->databaseType == $this->GEOIP_CITY_EDITION_REV0) ||
                        ($this->databaseType == $this->GEOIP_CITY_EDITION_REV1)
                        || ($this->databaseType == $this->GEOIP_ORG_EDITION)
                        || ($this->databaseType == $this->GEOIP_ORG_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION)
                        || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_ISP_EDITION)
                        || ($this->databaseType == $this->GEOIP_ISP_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_USERTYPE_EDITION)
                        || ($this->databaseType == $this->GEOIP_USERTYPE_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_LOCATIONA_EDITION)
                        || ($this->databaseType == $this->GEOIP_ACCURACYRADIUS_EDITION)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV0_V6)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV1_V6)
                        || ($this->databaseType == $this->GEOIP_NETSPEED_EDITION_REV1)
                        || ($this->databaseType == $this->GEOIP_NETSPEED_EDITION_REV1_V6)
                        || ($this->databaseType == $this->GEOIP_ASNUM_EDITION)
                        || ($this->databaseType == $this->GEOIP_ASNUM_EDITION_V6)) {
                        $this->databaseSegments = 0;
                        $buf = @shmop_read($this->shmid, $offset, $this->SEGMENT_RECORD_LENGTH);
                        for ($j = 0; $j < $this->SEGMENT_RECORD_LENGTH; $j++) {
                            $this->databaseSegments += (ord($buf[$j]) << ($j * 8));
                        }
                        if (($this->databaseType == $this->GEOIP_ORG_EDITION)
                            || ($this->databaseType == $this->GEOIP_ORG_EDITION_V6)
                            || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION)
                            || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION_V6)
                            || ($this->databaseType == $this->GEOIP_ISP_EDITION)
                            || ($this->databaseType == $this->GEOIP_ISP_EDITION_V6)) {
                            $this->record_length = $this->ORG_RECORD_LENGTH;
                        }
                    }
                    break;
                } else {
                    $offset -= 4;
                }
            }
            if (($this->databaseType == $this->GEOIP_COUNTRY_EDITION) ||
                ($this->databaseType == $this->GEOIP_COUNTRY_EDITION_V6) ||
                ($this->databaseType == $this->GEOIP_PROXY_EDITION) ||
                ($this->databaseType == $this->GEOIP_NETSPEED_EDITION)) {
                $this->databaseSegments = $this->GEOIP_COUNTRY_BEGIN;
            }
        } else {
            $filepos = ftell($this->filehandle);
            fseek($this->filehandle, -3, SEEK_END);
            for ($i = 0; $i < $this->STRUCTURE_INFO_MAX_SIZE; $i++) {
                $delim = fread($this->filehandle, 3);
                if ($delim == (chr(255) . chr(255) . chr(255))) {
                    $this->databaseType = ord(fread($this->filehandle, 1));
                    if ($this->databaseType == $this->GEOIP_REGION_EDITION_REV0) {
                        $this->databaseSegments = $this->GEOIP_STATE_BEGIN_REV0;
                    } else if ($this->databaseType == $this->GEOIP_REGION_EDITION_REV1) {
                        $this->databaseSegments = $this->GEOIP_STATE_BEGIN_REV1;
                    } else if (($this->databaseType == $this->GEOIP_CITY_EDITION_REV0)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV1)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV0_V6)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV1_V6)
                        || ($this->databaseType == $this->GEOIP_ORG_EDITION)
                        || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION)
                        || ($this->databaseType == $this->GEOIP_ISP_EDITION)
                        || ($this->databaseType == $this->GEOIP_ORG_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_ISP_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_LOCATIONA_EDITION)
                        || ($this->databaseType == $this->GEOIP_ACCURACYRADIUS_EDITION)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV0_V6)
                        || ($this->databaseType == $this->GEOIP_CITY_EDITION_REV1_V6)
                        || ($this->databaseType == $this->GEOIP_NETSPEED_EDITION_REV1)
                        || ($this->databaseType == $this->GEOIP_NETSPEED_EDITION_REV1_V6)
                        || ($this->databaseType == $this->GEOIP_USERTYPE_EDITION)
                        || ($this->databaseType == $this->GEOIP_USERTYPE_EDITION_V6)
                        || ($this->databaseType == $this->GEOIP_ASNUM_EDITION)
                        || ($this->databaseType == $this->GEOIP_ASNUM_EDITION_V6)) {
                        $this->databaseSegments = 0;
                        $buf = fread($this->filehandle, $this->SEGMENT_RECORD_LENGTH);
                        for ($j = 0; $j < $this->SEGMENT_RECORD_LENGTH; $j++) {
                            $this->databaseSegments += (ord($buf[$j]) << ($j * 8));
                        }
                        if (($this->databaseType == $this->GEOIP_ORG_EDITION)
                            || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION)
                            || ($this->databaseType == $this->GEOIP_ISP_EDITION)
                            || ($this->databaseType == $this->GEOIP_ORG_EDITION_V6)
                            || ($this->databaseType == $this->GEOIP_DOMAIN_EDITION_V6)
                            || ($this->databaseType == $this->GEOIP_ISP_EDITION_V6)) {
                            $this->record_length = $this->ORG_RECORD_LENGTH;
                        }
                    }
                    break;
                } else {
                    fseek($this->filehandle, -4, SEEK_CUR);
                }
            }
            if (($this->databaseType == $this->GEOIP_COUNTRY_EDITION) ||
                ($this->databaseType == $this->GEOIP_COUNTRY_EDITION_V6) ||
                ($this->databaseType == $this->GEOIP_PROXY_EDITION) ||
                ($this->databaseType == $this->GEOIP_NETSPEED_EDITION)) {
                $this->databaseSegments = $this->GEOIP_COUNTRY_BEGIN;
            }
            fseek($this->filehandle, $filepos, SEEK_SET);
        }
    }
}