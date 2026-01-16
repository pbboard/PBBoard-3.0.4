<br />
<div class="address_bar">{$lang['Control_Panel']} &raquo; {$lang['Maintenance']} &raquo;
<a href="index.php?page=fixup&amp;update_meter=1&amp;main=1"> {$lang['fixup']}</a></div>
<br />

<form id="singleCacheForm" onsubmit="return false;">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['Updated_sections_individually']}</td></tr>
        <tr align="center">
            <td class="row1">
                {$DoJumpList}
                <div id="single_progress_container" style="display:none; width:90%; margin:10px auto; border:1px solid #ccc; background:#f9f9f9; padding:5px; border-radius:5px;">
                    <div id="single_progress_bar" style="width:0%; background:#FF9800; height:20px; text-align:center; color:white; line-height:20px; font-size:12px; border-radius:3px; transition: width 0.3s;">0%</div>
                    <div id="single_progress_status" style="margin-top:5px; font-weight:bold; color:#333;">{$lang['Wait']}...</div>
                </div>
                <input type="button" id="single_start_btn" value="{$lang['Start_Updated']}" class="button" onclick="startSingleCacheUpdate();" />
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
function startSingleCacheUpdate() {
    var sec_id = $("#select_section").val();
    if(!sec_id || sec_id == "0"){
        alert("{$lang['Please_select_section']}");
        return;
    }

    $("#single_start_btn").attr("disabled", true).val("جاري التحديث...");
    $("#single_progress_container").show();
    $("#single_progress_bar").css("width","0%").text("0%");
    $("#single_progress_status").html("جاري تحديث القسم: " + sec_id);

    $.ajax({
        url: "index.php?page=fixup&update_meter=1&all_cache=1&ajax_process=1&sec_id=" + sec_id,
        type: "GET",
        cache: false,
        success: function(response){
            $("#single_progress_bar").css("width","100%").text("100%").css("background","#4CAF50");
            $("#single_progress_status").html("<span style='color:green;'>تم تحديث القسم بنجاح!</span>");
            setTimeout(function(){ location.reload(); }, 1500);
        },
        error: function(){
            $("#single_progress_status").html("<span style='color:red;'>خطأ أثناء التحديث.</span>");
            $("#single_start_btn").attr("disabled", false).val("{$lang['Start_Updated']}");
        }
    });
}
</script>


<br />

<form id="allCacheForm" onsubmit="return false;">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['Update_all_Forums_at_once']}</td></tr>
        <tr align="center">
            <td class="row1">
                <div id="progress_container" style="display:none; width:90%; margin:10px auto; border:1px solid #ccc; background:#f9f9f9; padding:5px; border-radius:5px;">
                    <div id="progress_bar" style="width:0%; background:#4CAF50; height:20px; text-align:center; color:white; line-height:20px; font-size:12px; border-radius:3px; transition: width 0.3s;">0%</div>
                    <div id="progress_status" style="margin-top:5px; font-weight:bold; color:#333;">{$lang['Wait']}...</div>
                </div>
                <input type="button" id="start_btn" value="{$lang['Start_Updated']}" class="button" onclick="startAllCacheUpdate();" />
            </td>
        </tr>
    </table>
</form>

<br />

<form action="index.php?page=fixup&amp;update_static=1" method="post">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['update_static']}</td></tr>
        <tr align="center"><td class="row1"><input type="submit" value="{$lang['Start_Updated']}" name="submit" /></td></tr>
    </table>
</form>

<br />

<form id="updatePostsForm" onsubmit="return false;">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['update_posts']}</td></tr>
        <tr>
            <td class="row1" style="padding:15px; line-height:1.6; color:#444; font-size:13px;">
                <strong>وصف الأداة (Data Synchronization):</strong>
                <p style="margin:5px 0;">تعمل هذه الخاصية على إعادة فحص وتدقيق كافة المشاركات في قاعدة البيانات، مزامنة الردود مع الأقسام، وإعادة بناء كاش الرد الأخير.</p>
            </td>
        </tr>
        <tr align="center">
            <td class="row2" style="padding:20px;">
                <div id="posts_progress_container" style="display:none; width:90%; margin:10px auto; border:1px solid #ccc; background:#f9f9f9; padding:5px; border-radius:5px;">
                    <div id="posts_progress_bar" style="width:0%; background:#4CAF50; height:20px; text-align:center; color:white; line-height:20px; font-size:12px; border-radius:3px; transition: width 0.3s;">0%</div>
                    <div id="posts_progress_status" style="margin-top:5px; font-weight:bold; color:#333;">يرجى الانتظار...</div>
                </div>
                <input type="button" id="posts_start_btn" value="{$lang['Start_Updated']}" class="button" onclick="startPostsUpdate();" />
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
/* ======================================================
   تحديث المشاركات (Posts Update)
====================================================== */
function startPostsUpdate() {
    var perPage = 100;
    var last_id = 0;
    var totalSubjects = 0;
    var baseUrl = "index.php?page=fixup&update_posts=1";

    $("#posts_start_btn").attr("disabled", true).val("جاري التحضير...");
    $("#posts_progress_container").show();
    $("#posts_progress_bar").css("width","0%").text("0%");

    $.ajax({
        url: baseUrl + "&get_total=1",
        type: "GET",
        dataType: "json",
        cache: false,
        success: function(data) {
            totalSubjects = parseInt(data.total);
            if(totalSubjects > 0){
                processNextPostPage();
            } else {
                $("#posts_progress_status").html("<span style='color:orange;'>لا توجد مواضيع لتحديث.</span>");
                $("#posts_start_btn").attr("disabled", false).val("ابدأ التحديث");
            }
        }
    });

    function processNextPostPage() {
        $.ajax({
            url: baseUrl + "&ajax_process=1&perpage=" + perPage + "&last_id=" + last_id,
            type: "GET",
            cache: false,
            timeout: 90000,
            success: function(response){
                if(response.indexOf("SUCCESS") !== -1){
                    var parts = response.split('|');
                    last_id = parseInt(parts[1]);

                    if(last_id > 0){
                        var percent = Math.round((last_id / totalSubjects) * 100);
                        if(percent > 100) percent = 100;

                        $("#posts_progress_bar").css("width", percent + "%").text(percent + "%");
                        $("#posts_progress_status").html("تحديث المواضيع... " + percent + "%");

                        setTimeout(processNextPostPage, 10);
                    } else {
                        $("#posts_progress_bar").css("width","100%").text("100%");
                        $("#posts_progress_status").html("<span style='color:green;font-weight:bold;'>تم تحديث جميع المواضيع!</span>");
                        setTimeout(function(){ location.reload(); }, 2000);
                    }
                } else {
                    console.log("رد غير متوقع من السيرفر.");
                    setTimeout(processNextPostPage, 500);
                }
            },
            error: function(xhr,status,error){
                console.log("خطأ في الدفعة بعد ID " + last_id + ": " + error);
                setTimeout(processNextPostPage, 1000);
            }
        });
    }
}

/* ======================================================
   تحديث جميع الأقسام (All Sections Cache Update)
====================================================== */
function startAllCacheUpdate() {
    var sections = [];
    $("#select_section option").each(function() {
        var val = $(this).val();
        if (val && val != "0") sections.push(val);
    });

    var total = sections.length;
    var current = 0;

    $("#start_btn").attr("disabled", true).val("جاري تحديث الكاش...");
    $("#progress_container").show();
    $("#progress_status").html("<b>الخطوة 1:</b> جاري تنظيف ملفات الكاش القديمة...");

    $.ajax({
        url: "index.php?page=fixup&update_meter=1&clear_all_files_cache=1",
        type: "GET",
        cache: false,
        success: function() {
            $("#progress_status").html("<b>الخطوة 2:</b> تم الحذف، جاري إعادة بناء الكاش...");
            processNext();
        },
        error: function() {
            $("#progress_status").html("<span style='color:red;'>خطأ في حذف الكاش، سيتم المحاولة مجدداً...</span>");
            setTimeout(processNext, 1000);
        }
    });

    function processNext() {
        if(current < total){
            var sec_id = sections[current];
            var percent = Math.round((current / total) * 100);

            $("#progress_bar").css("width", percent + "%").text(percent + "%");
            $("#progress_status").html("معالجة القسم: " + sec_id + " - يرجى الانتظار...");

            $.ajax({
                url: "index.php?page=fixup&update_meter=1&all_cache=1&ajax_process=1&sec_id=" + sec_id,
                type: "GET",
                cache: false,
                success: function() {
                    current++;
                    setTimeout(processNext, 200);
                },
                error: function() {
                    current++;
                    setTimeout(processNext, 200);
                }
            });
        } else {
            $("#progress_bar").css("width", "100%").text("100%").css("background", "#2196F3");
            $("#progress_status").html("<span style='color:green;'>اكتمل التحديث بنجاح!</span>");
            $.get("index.php?page=fixup&update_meter=1&main=1", function() {
                setTimeout(function(){ location.reload(); }, 1500);
            });
        }
    }
}
</script>


<br />
<!-- حذف الردود اليتيمة التي لا تتبع أي موضوع -->
<form id="deleteOrphanForm" onsubmit="deleteOrphanReplies(); return false;">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center">
            <td class="main1">حذف الردود اليتيمة (Orphan Replies)</td>
        </tr>
        <tr align="center">
            <td class="row1" style="padding:10px; font-size:13px; color:#555;">
                <p>
                    هذا الخيار يقوم بحذف جميع الردود الموجودة في قاعدة البيانات والتي لا تتبع أي موضوع (أي تم حذف الموضوع نهائياً أو لم يُربط بالموضوع أصلاً).
                    ينصح باستخدامه بعد تحديث المشاركات لضمان نظافة قاعدة البيانات.
                </p>
            </td>
        </tr>
        <tr align="center">
            <td class="row1">
                <input type="submit" value="حذف الآن" class="button" />
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
function deleteOrphanReplies() {
    if (!confirm('هل أنت متأكد من حذف جميع الردود اليتيمة التي لا تتبع أي موضوع؟')) return;

    $.ajax({
        url: 'index.php?page=fixup&delete_orphan_replies=1',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            alert(res.message);
        },
        error: function() {
            alert('حدث خطأ أثناء عملية الحذف!');
        }
    });
}
</script>


<br />
<form action="index.php?page=fixup&amp;update_username_members=1" method="post">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['update_username_members']}</td></tr>
        <tr align="center"><td class="row1"><input type="submit" value="{$lang['Start_Updated']}" name="submit" /></td></tr>
    </table>
</form>
<br />
<form action="index.php?page=fixup&amp;update_users_ratings=1" method="post">
    <table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
        <tr align="center"><td class="main1">{$lang['update_users_ratings']}</td></tr>
        <tr align="center"><td class="row1"><input type="submit" value="{$lang['Start_Updated']}" name="submit" /></td></tr>
    </table>
</form>
<br />

<form action="index.php?page=fixup&amp;update_users_titles=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['update_users_titles']}
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>

<br />


<form action="index.php?page=fixup&amp;repair_mem_posts=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="1">
{$lang['RepairMemberPosts']}
			</td>
		</tr>
				<tr>
			<td class="row1" colspan="2">
{$lang['RepairMemberPosts_not']}
<br />
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="text" name="perpage" size="35" value="200">
			</td>
		</tr>
				<tr align="center">
			<td class="row1">
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>



<br />

<form action="index.php?page=fixup&amp;update_meter=1&amp;groups=1" method="post">
	<table width="80%" class="t_style_b" border="0" cellspacing="1" align="center">
		<tr align="center">
			<td class="main1" colspan="2">
{$lang['Update_all_groups']}
			</td>

				<tr align="center">
			<td class="row1" colspan="2">
{$lang['annotation_Update_all_groups']}
<br />
<input type="submit" value="{$lang['Start_Updated']}" name="submit" />
			</td>
		</tr>
	</table>
</form>