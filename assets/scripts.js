jQuery(document).ready(function()
{
	
	dynamicInit();
	
});

function dynamicInit()
{
	$('.pushStateForm').submit(function(e)
	{
		e.preventDefault();
		
		$.ajax({
			type : 'POST',
			beforeSend : function()
			{
				if($('#loading').length > 0) $('#loading').show();
			},
			url : $(this).attr('action'),
			data : $(this).serialize(),
			dataType : 'html',
			success : function(response)
			{
				if($('#loading').length > 0) $('#loading').hide();
				
				const div = document.createElement('div');
				div.innerHTML = response;
				const newBlock = div.querySelector('.wrap')
				$('.wrap').empty().append(newBlock);
				
				dynamicInit();
			}
		});
		
		return false;
	});
}