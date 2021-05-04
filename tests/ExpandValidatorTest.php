<?php

class ExpandValidatorTest extends TestCase
{
    /** @test */
    public function ExpandValidatorTest_1()
    {
        $this->post('/expand_validator', 
        [
            "a.*.y.t"=>"integer",
            "a.*.y.u"=>"integer",
            "a.*.z"=>"object|keys:w,o",
            "b"=>"array",
            "b.c"=>"string",
            "b.d"=>"object",
            "b.d.e"=>"integer|min:5",
            "b.d.f"=>"string"
        ]);
        $this->seeStatusCode(200);
        $this->seeJson(
          [
            'a'=>[
               'type'=>'array',
               'validators'=>[
                  'array'
               ],
               'items'=>[
                  'type'=>'object',
                  'validators'=>[
                     'object'
                  ],
                  'properties'=>[
                     'y'=>[
                        'type'=>'object',
                        'validators'=>[
                           'object'
                        ],
                        'properties'=>[
                           't'=>[
                              'type'=>'leaf',
                              'validators'=>[
                                 'integer'
                              ]
                           ],
                           'u'=>[
                              'type'=>'leaf',
                              'validators'=>[
                                 'integer'
                              ]
                           ]
                        ]
                     ],
                     'z'=>[
                        'type'=>'object',
                        'validators'=>[
                           'object'
                        ],
                        'properties'=>[
                           'w'=>[
                              'type'=>'leaf',
                              'validators'=>[
                                 
                              ]
                           ],
                           'o'=>[
                              'type'=>'leaf',
                              'validators'=>[
                                 
                              ]
                           ]
                        ]
                     ]
                  ]
               ]
            ],
            'b'=>[
               'type'=>'object',
               'validators'=>[
                  'object'
               ],
               'properties'=>[
                  'c'=>[
                     'validators'=>[
                        'string'
                     ],
                     'type'=>'leaf'
                  ],
                  'd'=>[
                     'type'=>'object',
                     'validators'=>[
                        'object'
                     ],
                     'properties'=>[
                        'e'=>[
                           'validators'=>[
                              'integer',
                              'min:5'
                           ],
                           'type'=>'leaf'
                        ],
                        'f'=>[
                           'validators'=>[
                              'string'
                           ],
                           'type'=>'leaf'
                        ]
                     ]
                  ]
               ]
            ]
         ]
        );
    }

    /** @test */
    public function ExpandValidatorTest_2(){
      $this->post('/expand_validator', 
      [
          ""=>"integer",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_3(){
      $this->post('/expand_validator', 
      [
          "a.*.y.t"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_4(){
      $this->post('/expand_validator', 
      [
          "*.*.y.t"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_5(){
      $this->post('/expand_validator', 
      [
          "a.*.*.t"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_6(){
      $this->post('/expand_validator', 
      [
          "a.*.y.*"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_7(){
      $this->post('/expand_validator', 
      [
          "a.*.y.t"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o|keys:o",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }

    /** @test */
    public function ExpandValidatorTest_8(){
      $this->post('/expand_validator', 
      [
          "a.*.y.t"=>"",
          "a.*.y.u"=>"integer",
          "a.*.z"=>"object|keys:w,o:j",
          "b"=>"array",
          "b.c"=>"string",
          "b.d"=>"object",
          "b.d.e"=>"integer|min:5",
          "b.d.f"=>"string"
      ]);
      $this->seeStatusCode(400);
    }
}
