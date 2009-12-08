		<% if GalleryItems %>
		<ul class="gallery-layout" id="gallery-list">
			<% if GalleryItems.NotFirstPage %>
				<% control PreviousGalleryItems %>
							<li style="display:none;">$GalleryItem</li>
				<% end_control %>
			<% end_if %>
			<% control GalleryItems %>
				<li style="height:{$ThumbnailHeight}px;width:{$ThumbnailWidth}px;">
						$GalleryItem
				</li>
			<% end_control %>
			<% if GalleryItems.NotLastPage %>
				<% control NextGalleryItems %>
					<li style="display:none;">$GalleryItem</li>
				<% end_control %>
			<% end_if %>
		</ul>
		<% end_if %>
