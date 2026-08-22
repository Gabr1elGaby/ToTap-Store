<?php
// Fix creative
$creative = file_get_contents('resources/views/cv/templates/creative.blade.php');
$old_creative = '                @if(count($skills) > 0)
                <div class="left-header">Skills</div>
                <div style="margin-bottom: 20px;">
                    @foreach($skills as $skill)
                    <div style="margin-bottom: 8px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                            <tr>
                                <td style="font-size: 9.5pt;">{{ $skill->name }}</td>
                                @if(isset($skill->level))
                                <td align="right" style="font-size: 9.5pt; color: #38bdf8;">{{ $skill->level }}%</td>
                                @endif
                            </tr>
                        </table>
                        @if(isset($skill->level))
                        <div style="width: 100%; background-color: #334155; height: 4px; border-radius: 2px;">
                            <div style="width: {{ $skill->level }}%; background-color: #38bdf8; height: 100%; border-radius: 2px;"></div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif';
$new_creative = '                @if(count($hard_skills) > 0)
                <div class="left-header">Hard Skills</div>
                <div style="margin-bottom: 20px;">
                    @foreach($hard_skills as $skill)
                    <div style="margin-bottom: 8px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                            <tr>
                                <td style="font-size: 9.5pt;">{{ $skill->name }}</td>
                                <td align="right" style="font-size: 9.5pt; color: #38bdf8;">{{ $skill->level }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #334155; height: 4px; border-radius: 2px;">
                            <div style="width: {{ $skill->level }}%; background-color: #38bdf8; height: 100%; border-radius: 2px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(count($soft_skills) > 0)
                <div class="left-header">Soft Skills</div>
                <ul class="skill-list" style="margin-bottom: 20px;">
                    @foreach($soft_skills as $skill)
                    <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
                @endif';
$creative = str_replace($old_creative, $new_creative, $creative);
file_put_contents('resources/views/cv/templates/creative.blade.php', $creative);

// Fix student
$student = file_get_contents('resources/views/cv/templates/student.blade.php');
$old_student = '                @if(count($skills) > 0)
                <div><span class="right-header">Kemampuan (Skills)</span></div>
                <div style="padding-left: 15px;">
                    @foreach($skills as $skill)
                    <div style="margin-bottom: 12px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 4px;">
                            <tr>
                                <td style="font-size: 10pt; color: #334155; font-weight: bold;">{{ $skill->name }}</td>
                                @if(isset($skill->level))
                                <td align="right" style="color: #831843; font-size: 9pt;">{{ $skill->level }}%</td>
                                @endif
                            </tr>
                        </table>
                        @if(isset($skill->level))
                        <div style="width: 100%; background-color: #f1f5f9; height: 6px; border-radius: 3px;">
                            <div style="width: {{ $skill->level }}%; background-color: #831843; height: 100%; border-radius: 3px;"></div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif';
$new_student = '                @if(count($hard_skills) > 0)
                <div><span class="right-header">Hard Skills</span></div>
                <div style="padding-left: 15px; margin-bottom: 20px;">
                    @foreach($hard_skills as $skill)
                    <div style="margin-bottom: 12px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 4px;">
                            <tr>
                                <td style="font-size: 10pt; color: #334155; font-weight: bold;">{{ $skill->name }}</td>
                                <td align="right" style="color: #831843; font-size: 9pt;">{{ $skill->level }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #f1f5f9; height: 6px; border-radius: 3px;">
                            <div style="width: {{ $skill->level }}%; background-color: #831843; height: 100%; border-radius: 3px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(count($soft_skills) > 0)
                <div><span class="right-header">Soft Skills</span></div>
                <ul class="skill-list" style="padding-left: 30px; margin-bottom: 20px;">
                    @foreach($soft_skills as $skill)
                    <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
                @endif';
$student = str_replace($old_student, $new_student, $student);
file_put_contents('resources/views/cv/templates/student.blade.php', $student);

echo "Done fixing creative and student.\n";
