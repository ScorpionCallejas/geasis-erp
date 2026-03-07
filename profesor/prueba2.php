<html lang="en">
<head>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.css">

	<style>
		body, button, input, select, textarea {
		    font-family: "Open Sans",open-sans,sans-serif;
		}

		ul, li { list-style: none; margin: 0; padding: 0; }

		.area {
		  background-color: #E4E4E4;
		  min-height: 900px;
		  padding-top: 100px;
		  text-align: center;
		}
		.cardy {
		  width: 400px;
		  background-color: #fff;
		  box-shadow: 0 0 10px rgba(0, 0, 0, .5);
		  border-radius: 4px;
		  margin:0 100px 0 auto;
		  padding-top: 18px;
		  .section {
		    padding: 8px 18px;
		    border-bottom: 1px solid #E6E6E6;
		    .title {
		      color: #666;
		      font-size: 15px;
		      margin-bottom: 10px;
		    }
		    &:last-child {
		      border: none;
		    }
		    .bottom-bar {
		      position: absolute;
		      bottom: 18px;
		      left:18px;
		      right:18px;
		      .btn-icon {
		        width: 60px;
		        display: block;
		        height: 60px;
		        border-radius: 50%;
		        background-color: aliceblue;
		        text-align: center;
		        font-size: 11px;
		        &:hover {
		          text-decoration: none;
		        }
		        span {
		          display: block;
		          font-size: 24px;
		          padding: 7px 0 4px 0;
		        }
		      }
		    }
		  }
		  .caption {
		    // text-transform: uppercase;
		    text-align: center;
		    font-size: 11px;
		    margin: -10px 0 00 ;
		    color: #41B7BF;
		  }
		  
		}
		.cardy-side {  
		  position: relative;
		  width: 280px;
		  min-height: 475px;  
		}
		input[type=text] {
		    display: inline-block;
		    height: 17px;
		    padding: 4px;
		    margin-bottom: 9px;
		    font-size: 12px;
		    line-height: 17px;
		    color: #555;
		}


		.input-layout {
		  position: relative;
		  margin-top: 28px;
		  label {
		    color: #b8b5bd;
		    font-size: 18px;
		    font-weight: 400;
		    letter-spacing: .5px;
		    position: absolute;
		    pointer-events: none;
		    left: 0;
		    top: 2px;
		    -webkit-transition: .2s ease all;
		    transition: .2s ease all;
		  }
		  input[type=text] {
		    font-weight: 400;
		    font-size: 26px;
		    color: #1E2431;
		    border: 0;
		    width: 100%;
		    height: 30px;
		    padding: 1px 0 10px 2px;
		    border-bottom: 1px solid #dadee6;
		    box-shadow: none;
		    -webkit-transition: none;
		    transition: none;
		    border-radius: 0;
		    &:focus {
		      outline: 0;
		      padding-left: 0;
		      text-transform: none;
		      color: #1E2431;
		    }
		  }
		  input[type=text]:focus~label, input[type=text]:valid~label {
		    top: -24px;
		    color: #A2AABD;
		    opacity: 1;
		    font-weight: 400;
		    font-size: 11px;
		    text-transform: uppercase;

		  }
		  .bar {
		      position: relative;
		      display: block;
		      width: 100%;
		  }
		  .bar:after, .bar:before {
		      content: '';
		      height: 1px;
		      width: 0;
		      bottom: 9px;
		      position: absolute;
		      background: #41B7BF;
		      -webkit-transition: .2s ease all;
		      transition: .2s ease all;
		  }
		  .bar:before {
		      left: 50%;
		  }
		  .bar:after {
		      right: 50%;
		  }
		  input:focus~.bar:after, input:focus~.bar:before {
		      width: 50%;
		  }
		}


		.gs-act {
		  font-weight: bold;
		}
		.pop-content {
		  display: none;  
		}
		.popover {  
		  min-width: 500px;
		  max-width: 600px;

		}
		.ee {
		  position: relative;
		  .kb {
		    clear: both;
		    overflow: hidden;
		    margin: 0 -15px -10px -15px;
		    .kbrow {
		      a {      
		        color: #fff;
		        text-decoration: none;
		        text-align: center;
		        display: block;
		        float: left;
		        height: 42px;
		        line-height: 42px;
		        width: 10%;      
		        border:1px solid #888C92;
		        border-width:0 1px 1px 0;
		        background-color: #444444;
		        background-image: -webkit-linear-gradient(top, #2D343E, #0D1520);        
		        &:hover {
		          background:#0088E3;
		          text-shadow: 0 -1px 0 #666;
		        }
		      }
		      a.cmd {
		        background-color: #d47b2c;
		        background: -webkit-linear-gradient(top, #f9c46c 0%,#d47b2c 100%);
		      }
		    }
		    .kbrow:last-child a {
		      &:first-child {
		          border-radius: 0 0 0 4px;
		      }
		      &:last-child {
		        border-radius: 0 0 4px 0;
		      }
		    }
		  }  
		}
		#MQinput {
		  font-size: 50px;
		  color:#222;
		  margin-bottom: 9px;
		  border: none;
		}
		#MQinput.mq-focused {
		  -webkit-box-shadow: none;
		  -moz-box-shadow: none;
		  box-shadow: none;
		  border-color: transparent;
		}
	</style>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>



</head>
	<body>
		
		<script type="text/x-mathjax-config">
		  MathJax.Hub.Config({
		    tex2jax: { inlineMath: [['$', '$']] },
		    CommonHTML: {
		      scale: 100
		    }
		  });
		</script>
		<div class="area">
		  Reference: <a href="http://math.chapman.edu/~jipsen/mathquill/test/test.html" target="_blank" >MathQuill </a> and 
		  <a target="_blank" href="https://www.artofproblemsolving.com/wiki/index.php?title=LaTeX:Commands">Latex</a><br/>
		  LATEX Output -><span id="latex">Make an equation.</span>
		  <br/><br/>
		<p><a href="#" class="btn btn-primary">Fill in Equation</a></p>
		    </div>      
		    
		  </div>
		</div>

		<div class="pop-content">
		  <div id="p1">
		    <div class="ee">
		      <div id="MQinput"></div>
		      <div id="MQkeyboard" class="kb"></div>
		    </div>
		  </div>
		</div>

		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/mathquill/0.10.1/mathquill.min.js"></script>
		<script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>


		<script>
			
		$ ->
		  MQF = null
		  config = {
		    handlers: { 
		      edit: ()->
		        console.log(1111)
		        # enteredMath = MQF.latex()
		        # console.log(enteredMath)
		    },
		    restrictMismatchedBrackets: true
		  }
		  MQ = MathQuill.getInterface(1)  
		      
		  $(".btn").popover({
		    'title' : 'Enter Equation', 
		    'html' : true,
		    'placement' : 'bottom', 
		    'content' : $('#p1').html()
		  });

		  editorPopoverSize = { w: 0, h: 0 }
		  $(document.body).on 'shown.bs.popover', ->
		    keys = [
		      [
		        { v:'0', l:'0' }
		        ,{ v:'1', l:'1' }
		        ,{ v:'2', l:'2' }
		        ,{ v:'3', l:'3' }
		        ,{ v:'4', l:'4' }
		        ,{ v:'5', l:'5' }
		        ,{ v:'6', l:'6' }
		        ,{ v:'7', l:'7' }
		        ,{ v:'8', l:'8' }
		        ,{ v:'9', l:'9' }
		      ],
		      [
		        { c: true, v:'.', l:'$.$' }
		        ,{ c: true, v:'+', l:'$+$' }
		        ,{ c: true, v:'-', l:'$-$' }
		        ,{ c: true, v:'*', l:'$*$' }
		        ,{ v:'/', l:'$/$' }
		        # ,{ v:'\\div', l:'$\\div$' }
		        ,{ v:'=', l:'$=$' }
		        ,{ c: true, v:'\\frac', l:'$\\frac{1}{2}$' }
		        ,{ c: true, v:'\\sqrt', l:'$\\sqrt{n}$' }
		        ,{ c: true, v:'^', l:'$n^2$' }
		        ,{ c: true, v:'\\pi', l:'$\\pi$' }
		      ]
		    ]
		    history = []
		    kb = $('#MQkeyboard')
		    for set in keys
		      row = $('<div/>', { 'class': 'kbrow'})
		      kb.append(row)
		      for key in set  
		        isCmd = if key.c then 1 else 0
		        keyClass = if key.c then 'cmd' else 'num'
		        row.append('<a class="' + keyClass + '" href="#" data-mq-cmd="' + isCmd + '" data-mq="' + key.v + '">' + key.l + '</a>')

		    MathJax.Hub.Queue(['Typeset', MathJax.Hub, kb[0]])
		    MQF = MQ.MathField($('#MQinput')[0], config)
		    if true #normal
		      MQF.latex('x=-b\\pm \\sqrt b^2 -4ac')
		    else #templated
		      MQF = MQ.StaticMath($('#MQinput')[0], config)    
		      MQF.latex('=\\MathQuillMathField{}')
		      MQF.innerFields[0].focus()
		    
		    kb.on 'click', 'a', (e) ->
		      e.preventDefault()
		      dataMQ = $(this).data('mq')
		      isCmd = $(this).data('mq-cmd') == 1
		      
		      if true #normal
		        MQF[if isCmd then 'cmd' else 'write'](dataMQ)
		      else #templated
		        MQF.innerFields[0][if isCmd then 'cmd' else 'write'](dataMQ)      
		        MQF.innerFields[0].focus()
		      
		      
		      $('#latex').text(MQF.latex())
		      history.push(dataMQ)
		      console.log(history)
		  
		  
		</script>
	</body>
</html>