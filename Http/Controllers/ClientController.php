<?php
namespace Modules\Product\Http\Controllers;
use Elasticsearch\Client;
use Modules\Core\Http\Controllers\BasePublicController;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client as ElasticaClient;

class ClientController extends BasePublicController
{

    protected $elasticsearch;
    protected $elastica;
    /**
     * ClientController constructor.
     */
    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()->build();
        //$this->elasticsearch = $elasticsearch;
        $elasticaConfig = [
            'index'=>'ast',
            'type' => 'product__products',
            'body' => [

            ]
        ];
        $this->elastica = $this->elasticsearch->index($elasticaConfig);
    }

    public function elasticsearchTest()
    {
         dump($this->elasticsearch);
         echo "\n\n Retrieve a document\n\n";
         $params = [
             'index' => 'ast',
             'type'=>'product__products',
             'id' => 13
         ];
         $response = $this->elasticsearch->get($params);
         dump($response);
    }

    public function elasticsearchQueries()
    {
        $query  = request('q');

        $params = [
            'index' => 'ast',
            'type'=>'product__products',
            'size' => 15,
            'body' => [
                'query' => [
                    'multi_match' => [ "fields"=> [ "text", "title" ],
                      "query"=>    "SURPRIZE ME!",
                      "fuzziness"=> "AUTO"
                    ]
                ]
            ]
        ];
        $response = $this->elasticsearch->search($params);
        dump($response);


        $params = [
            'index' => 'ast',
            'type'=>'product__products',
            'size' => 15,
            'body' => [
                'query' => [
                    'bool' => [
                        'must'=> [
                            'match' => ['title' => $query]
                        ],
                        'should'=> [
                            'term' => ['title' => $query],
                            'term' => ['description' => $query]
                        ],
                        'filter'=> [
                            'range' => ['created_at' => [
                                'gte'=>'2015-01-01'
                            ]]
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->elasticsearch->search($params);
        dump($response);

    }
}