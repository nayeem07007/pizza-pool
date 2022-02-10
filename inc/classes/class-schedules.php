<?php 
namespace pizza\pool;

class schedules {
    
    public function pool_schedules () {
        /**
         * Get default resturent schedules from doc,  should be comes from options table in future 
         * @package schedules 
         * @return resturent schedules
         */
        $schedules = [
            'Thursday'=> '16-22',
            'Friday'=> '12-2',
            'Saturday'=> '12-22',
        ];
        return $schedules;
    }

    public function is_pizza_pool_open () {
        /**
         * Check is pizza pool open or close with current time
         * @package open hour
         * @return  pizza pool open or close 
         */

        $default_time_zon = 'Asia/Dhaka'; // default timezon if missing time zoon from wp setting options 

        if(get_option('timezone_string') != '') {
            $default_time_zon  = get_option('timezone_string');
        }
        
        $dt = new \DateTime('now', new \DateTimezone($default_time_zon));
         
        if(array_key_exists($dt->format('l'), $this->pool_schedules())) {

            $opning_hour_bet = $this->pool_schedules()[$dt->format('l')];
            $opning_hour = $dt->format('H');

            $time_between = explode('-', $opning_hour_bet);
            $time_wrange = range($time_between[0], $time_between[1]);
            
            if(in_array($opning_hour, $time_wrange)) {
                return 'open';
            }else{
                $open_time = '12MP';
                if($time_between[0] == 16) {
                    $open_time = '4MP';
                }
                $today = $dt->format('l');
                return esc_html__("$today: open time $open_time and Close Time 10 PM", 'pizza-pool');
            }
        }else{
            return esc_html__('Today Resturent Close', 'pizza-pool');
        }

    }
}