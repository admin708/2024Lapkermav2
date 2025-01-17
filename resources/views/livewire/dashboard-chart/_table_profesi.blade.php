@php
    $totalIntProfesi= 0;
    $totalLokProfesi = 0;
    $totalProdiIntProfesi = 0;
    $totalProdiLokProfesi = 0;
    $totalProdiKerjasamaProfesi = 0;
@endphp
<div id="top-categories" class="top-cat-area" style="padding-bottom: 70px">
    <div class="container">
        <h3>JENJANG PROFESI</h3>
        <div class="row">
            <div class="top-cat-items">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PROGRAM STUDI</th>
                                <th>FAKULTAS</th>
                                <th>JENJANG</th>
                                <th>LUAR NEGERI (1)</th>
                                <th>DALAM NEGERI (0,5)</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                          @foreach ($getProdi->where('jenjang','profesi') as $item)
                            @php
                                $count = 0;
                                $count2 = 0;
                                $count3 = 0;
                                $count4 = 0;
                            @endphp
                          <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>{{$item->nama_resmi}}</td>
                              <td>{{$item->fakultas->nama_fakultas}}</td>
                              <td class="text-uppercase" width="13%">
                                PROFESI
                              </td>
                              <td width="13%">
                                @foreach ($item->getMoa->where('jenis_kerjasama', 2) as $moaInt)
                                  @if ($year)
                                      @if (substr($moaInt->tanggal_ttd, 0, 4) == $year)
                                          @php
                                              $count++;
                                              $totalIntProfesi++;
                                          @endphp
                                      @endif
                                  @else
                                      @php
                                        $count++;
                                        $totalIntProfesi++;
                                      @endphp
                                  @endif
                                @endforeach
                                @foreach ($item->getIa->where('jenis_kerjasama', 2) as $iaInt)
                                  @if ($year)
                                      @if (substr($iaInt->tanggal_ttd, 0, 4) == $year)
                                          @php
                                              $count2++;
                                              $totalIntProfesi++;
                                          @endphp
                                      @endif
                                  @else
                                      @php
                                        $count2++;
                                        $totalIntProfesi++;
                                      @endphp
                                  @endif
                                @endforeach
                                {{$count+$count2}}
                                @php
                                    if ($count+$count2 != 0) {
                                      $totalProdiIntProfesi++;
                                    }
                                @endphp
                              </td>
                              <td width="13%">
                                @foreach ($item->getMoa->where('jenis_kerjasama', 1) as $moa)
                                  @if ($year)
                                      @if (substr($moa->tanggal_ttd, 0, 4) == $year)
                                          @php
                                              $count3++;
                                              $totalLokProfesi++;
                                          @endphp
                                      @endif
                                  @else
                                      @php
                                        $count3++;
                                        $totalLokProfesi++;
                                      @endphp
                                  @endif
                                @endforeach
                                @foreach ($item->getIa->where('jenis_kerjasama', 1) as $ia)
                                  @if ($year)
                                      @if (substr($ia->tanggal_ttd, 0, 4) == $year)
                                          @php
                                              $count4++;
                                              $totalLokProfesi++;
                                          @endphp
                                      @endif
                                  @else
                                      @php
                                        $count4++;
                                        $totalLokProfesi++;
                                      @endphp
                                  @endif
                                @endforeach
                                {{$count3+$count4}}
                                @php
                                    if ($count3+$count4 != 0) {
                                      $totalProdiLokProfesi++;
                                    }
                                @endphp
                              </td>
                              @if ($count+$count2+$count3+$count4 != 0)
                                  @php
                                      $totalProdiKerjasamaProfesi++;
                                  @endphp
                              @endif
                          </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">
                                    Total Dokumen Kerjasama
                                </td>
                                <td width="13%">{{$totalIntProfesi}}</td>
                                <td width="13%">{{$totalLokProfesi}}</td>
                            </tr>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">
                                    Total Prodi yang memiliki Kerjasama
                                </td>
                                <td width="13%">{{$totalProdiIntProfesi}}</td>
                                <td width="13%">{{$totalProdiLokProfesi}}</td>
                            </tr>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">Total Jumlah Prodi Profesi</td>
                                <td colspan="2"> {{$totalProdiProfesi}}</td>
                            </tr>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">Total Jumlah Prodi Profesi yang Memiliki Kerjasama</td>
                                <td colspan="2">{{$totalProdiKerjasamaProfesi}} / {{$totalProdiProfesi}} = {{round($totalProdiKerjasamaProfesi/$totalProdiProfesi*100)}}%</td>
                              </tr>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">1. Luar Negeri</td>
                                <td colspan="2">{{$totalProdiIntProfesi}} / {{$totalProdiProfesi}} = {{round($totalProdiIntProfesi/$totalProdiProfesi*100)}}%</td>
                            </tr>
                            <tr style="font-weight:700">
                                <td class="text-uppercase">2. Dalam Negeri</td>
                                <td colspan="2">{{$totalProdiLokProfesi}} / {{$totalProdiProfesi}} = {{round($totalProdiLokProfesi/$totalProdiProfesi*100)}}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>