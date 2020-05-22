        $apiUrl = 'http://www.some-api.com/bets';
        $html = '<div id="bets"><div><h3>2020 republican Presidential Nominee</h3><ul><li>Anyone other than Donald Trump<span class="odds">21.00</span></li></ul></div><div><h3>Donald Trump</h3><ul><li>No<span class="odds">10.00</span></li></ul></div><div><h3>2020 US Presidential Election</h3><ul><li>Republican<span class="odds">1.60</span></li><li>Democrat<span class="odds">2.25</span></li><li>Independant<span class="odds">51.00</span></li></ul></div></div>';

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $results = [];
        for ($i = 1; $i <= $xpath->query('//div[@id="bets"]/div')->count(); $i++) {
            $results[$i-1]['betName'] = $xpath->query('//div[@id="bets"]/div['.$i.']/h3')->item(0)->nodeValue;
            for ($y = 1; $y <= $xpath->query('//div[@id="bets"]/div['.$i.']/ul/li')->count(); $y++) {
                $option['outcome'] = $xpath->query('//div[@id="bets"]/div['.$i.']/ul/li['.$y.']')->item(0)->firstChild->nodeValue;
                $option['odds'] = $xpath->query('//div[@id="bets"]/div['.$i.']/ul/li['.$y.']/span')->item(0)->nodeValue;
                $results[$i-1]['betOptions'][] = $option;
            }
        }

        foreach ($results as $result) {
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($result));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
        }
