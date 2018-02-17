<?php

        namespace App\DomainHelper;
        use \DateTime as Date;

        trait DateTrait{
              private $date;  

              public function validDate(string $date,string $format="m/d/Y g:i"){
                     $date_only =  trim(str_ireplace(["pm",'am'],"",$date));
                     $checkDate =  Date::createFromFormat($format, $date_only);
                     $checkDate =  ($checkDate && $checkDate->format($format) == $date_only) ? true : false;
                     if($checkDate === true){
                        $this->date = new Date($date_only);
                        $now = new Date("now");
                        $this->dateParser($date);
                        if($this->date > $now->format('Y-m-d g:i')){//In Future.
                                return true;
                        }
                     }
                        return false;
              }
              /**
               * @method dateParser Parse The Date That Coming From DatetimePicker To Date Vailed By Mysql Datetime (2018-02-28 12:05:00)
               * @convert date=>(2018/02/28 12:05 (PM|AM))) To date=>(2018-02-28 12:05).
               * @return date=>(2018-02-28 12:05)
               */
              private function dateParser($date){
                      $date_items = explode(" ",$date);
                      if(is_array($date_items)){
                            $this->date = (isset($date_items[2])) ? 
                            ((strtolower($date_items[2]) == 'pm' && stripos($date_items[1],"12:") === false) ? 
                            date_add($this->date,date_interval_create_from_date_string('+12 hour')) : $this->date) : null;
                            $this->date = $this->date->format('Y-m-d g:i');
                            if(strtolower($date_items[2]) == 'am' && stripos($this->date,"12:")){//Turn Of 12: To 00: At Am Clock.
                                    $this->date = str_ireplace('12:',"00:",$this->date);
                            }
                      }   
              }

        }

        