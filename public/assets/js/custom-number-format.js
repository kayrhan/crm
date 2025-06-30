// Jquery Dependency

      $(document).on("keyup","input[data-type='currency']",function(){

            formatCurrency($(this),null,2);


      });

        $(document).on("blur","input[data-type='currency']",function(){

             formatCurrency($(this), "blur",2);


      });

        $(document).on("keyup","input[data-type='currency4']",function(){

            formatCurrency($(this),null,4);


      });

        $(document).on("blur","input[data-type='currency4']",function(){

             formatCurrency($(this), "blur",4);


      });



      function formatNumber(n,right=null) {
         // format number 1000000 to 1,234,567
          let minus = "";
          if(n.charAt(0) == "-"){
              minus = "-";
              n.replace("-","");
          }
          if(right === 4)
            return n.replace(/\D/g, "").replace(/\B(?=(\d{4})+(?!\d))/g, ".")
          else
            return minus+n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      }


      function formatCurrency(input, blur,decimal) {
         // appends $ to value, validates decimal side
         // and puts cursor back in right position.

         // get input value
         var input_val = input.val();

         // don't validate empty input
         if (input_val === "") {
            return;
         }

         // original length
         var original_len = input_val.length;

         // initial caret position
         var caret_pos = input.prop("selectionStart");

         // check for decimal
         if (input_val.indexOf(",") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(",");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
             if(decimal === 4)// if you want after "," 4 decimail
                right_side = formatNumber(right_side,4);
             else
                right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                if(decimal === 2)
                    right_side += "00";
                else if(decimal === 4){
                    right_side += "0000";
                }
            }

            // Limit decimal to only 2 digits
             if(decimal ===2)
                    right_side = right_side.substring(0, 2);
              else if(decimal ===4)
                    right_side = right_side.substring(0, 4);

            // join number by .
            input_val = left_side + "," + right_side;

         } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);


            // final formatting
            if (blur === "blur") {
                if(decimal === 2)
                    input_val += ",00";
                else if(decimal === 4)
                    input_val += ",0000";
            }
         }

         // send updated string to input
         input.val(input_val);

         // put caret back in the right position
         var updated_len = input_val.length;
         caret_pos = updated_len - original_len + caret_pos;
         input[0].setSelectionRange(caret_pos, caret_pos);
      }
