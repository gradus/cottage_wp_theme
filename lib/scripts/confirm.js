/*---:[ copyright notice ]:----------------------------------------------------

The CSS, JavaScript, and images contained in Thesis are all released under the
Thesis Proprietary Use License and cannot be redistributed. Doing so will
result in termination of membership with DIYthemes.

The custom.css file and any images in the custom images folder do NOT fall
under the Thesis Proprietary Use License. The end user determines the license
that should be applied here (if applicable).

The jscolor color picker script and associated images do NOT fall under the
Thesis Proprietary Use License and are free for use as determined by the GNU
Lesser GPL.

For more information on Thesis licensing and the Terms of Service, please see
the terms_of_service.txt file included in this package.

-----------------------------------------------------------------------------*/

function confirm_choice(kind, type) {
	if (kind == "default")
		var confirmed = confirm("Whoa there! Are you sure you want to restore "+type+" Options defaults? Unless you’ve made a backup of your current settings, this cannot be undone!");
	else if (kind == "upload")
		var confirmed = confirm("Are you sure you want to upload and overwrite "+type+" Options? Unless you’ve made a backup of your current settings, this cannot be undone!\n\nAlso, NEVER attempt to upload Thesis option files obtained from illegal download sites or untrustworthy sources; doing so could compromise your site’s security.");
	if (confirmed) return true;
	else return false;
}