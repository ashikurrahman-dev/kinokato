<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Courier</th>
                <th>Total</th>
                <th>Delivered</th>
                <th>Returned</th>
                <th>Success Ratio</th>
            </tr>
        </thead>
        <tbody> 
   
            <tr class="delivered-row">
                <td>Steadfast New</td>
                <td>{{$snt}}</td>
                <td>{{$snd}}</td>
                <td>{{$snc}}</td>
                @if($snt>0)
                 <td>{{intval(($snd/$snt)*100)}}%</td>
                @else
                 <td>0%</td>
                @endif
               
            </tr> 
            <tr class="delivered-row">
                <td>Steadfast Old</td>
                <td>{{$st}}</td>
                <td>{{$sd}}</td>
                <td>{{$sc}}</td>
                @if($st>0)
                 <td>{{intval(($sd/$st)*100)}}%</td>
                @else
                 <td>0%</td>
                @endif
                
            </tr> 
            <tr class="delivered-row">
                <td>RedX</td>
                <td>{{$rt}}</td>
                <td>{{$rd}}</td>
                <td>{{$rc}}</td>
                @if($rt>0)
                 <td>{{intval(($rd/$rt)*100)}}%</td>
                @else
                 <td>0%</td>
                @endif
                
            </tr> 
            <tr class="delivered-row">
                <td>Pathao</td>
                <td>{{$ptt}}</td>
                <td>{{$ptd}}</td>
                <td>{{$ptc}}</td>
                @if($ptt>0)
                 <td>{{intval(($ptd/$ptt)*100)}}</td>
                @else
                 <td>0%</td>
                @endif
                
            </tr> 
            <tr class="delivered-row">
                <td>Paperfly</td>
                <td>{{$pt}}</td>
                <td>{{$pd}}</td>
                <td>{{$pc}}</td>
                @if($pt>0)
                 <td>{{intval(($pd/$pt)*100)}}%</td>
                @else
                 <td>0%</td>
                @endif
                
            </tr> 
            <tr class="font-bold">
                <td>Total</td>
                <td>{{$pt+$ptt+$rt+$st+$snt}}</td>
                <td>{{$pd+$ptd+$rd+$sd+$snd}}</td>
                <td>{{$pc+$ptc+$rc+$sc+$snc}}</td>
                <td>{{$success}}%</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="progress mb-3">
  <div class="progress-bar" role="progressbar" style="width: {{$success}}%;" aria-valuenow="{{$success}}" aria-valuemin="0" aria-valuemax="100">Success Ratio : {{$success}}%</div>
</div>

<div class="progress">
  <div class="progress-bar" role="progressbar" style="background:red;width: {{$cancel}}%;" aria-valuenow="{{$cancel}}" aria-valuemin="0" aria-valuemax="100">Cancel Ratio : {{$cancel}}%</div>
</div>