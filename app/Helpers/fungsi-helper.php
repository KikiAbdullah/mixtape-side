<?php
function st_aktif($var){
    if(empty($var)){
        return "<center><div class=\"badge bg-primary\">Enabled</div></center>";
    }else{
        return "<center><div class=\"badge bg-light text-body\">Disabled</div></center>";
    }
}

function cleanNumber($val){
    return  str_replace('.00', '', number_format($val, 2,'.',',')); 
}

function namaBulan($var){
    switch ($var) {
        case 1:
          $result = "Januari";
          break;
        case 2:
            $result = "Februari";
            break;
        case 3:
            $result = "Maret";
            break;
        case 4:
            $result = "April";
            break;
        case 5:
            $result = "Mei";
            break;
        case 6:
            $result = "Juni";
            break;
        case 7:
            $result = "Juli";
            break;
        case 8:
            $result = "Agustus";
            break;
        case 9:
            $result = "September";
            break;
        case 10:
            $result = "Oktober";
            break;
        case 11:
            $result = "November";
            break;
        case 12:
            $result = "Desember";
            break;
        default:
            $result = "";
      }
      return $result;
}
?>