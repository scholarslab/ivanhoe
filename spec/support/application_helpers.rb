require 'csv'
require 'date'
require 'fileutils'
require 'mime/types'
require 'exifr'

module ApplicationHelper

  def dump_table(dump_dir, table_name)
    output  = "#{dump_dir}/#{table_name}.csv"
    results = @cxn[table_name.to_sym].all
    CSV.open(output, 'wb', :write_headers => true) do |csv|
      unless results.empty?
        fields = results.first.keys.sort
        csv << fields
        results.each do |row|
          csv << fields.map { |key| row[key] }
        end
      end
    end
  end

  def db_dump
    now      = Time.new
    dump_dir = "db-dump-#{now.strftime '%Y%m%d-%H%M%S.%L'}"
    Dir.mkdir dump_dir

    @cxn["SHOW TABLES;"]
      .map    { |row|   row[:Tables_in_test_ivanhoe] }
      .reject { |table| table.start_with? "copy_wp"  }
      .each   { |table| dump_table dump_dir, table   }
  end


  def tiny_mce_fill_in(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_content(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_rationale(name, args)
    page.execute_script("tinymce.editors[1].setContent('#{args[:with]}')")
  end

  def post_thumbnail(author, src, post_id)
    today      = Date.today
    ext        = File.extname(src)
    basename   = File.basename(src, ext)
    upload_dir = "uploads/%04d/%02d" % [today.year, today.month]
    FileUtils::mkdir_p "#{WP_DIR}/wp-content/#{upload_dir}"
    n = 0
    begin
      path  = "%s/%s%02d%s" % [upload_dir, basename, n, ext]
      dest  = "#{WP_DIR}/wp-content/#{path}"
      url   = "#{URL_BASE}/#{path}"
      n    += 1
    end while File.exists?(dest)
    FileUtils::cp(src, dest)

    exif  = EXIFR::JPEG.new(dest)
    exifh = exif.to_hash
    exifh.delete :orientation
    exifh.delete :date_time
    exifh.delete :date_time_original
    exifh.delete :date_time_digitized
    img_meta = {
        :file       => path.slice('uploads/'.size),
        :width      => exif.width,
        :height     => exif.height,
        :image_meta => exifh,
    }
    mime_types = MIME::Types.type_for(dest)
    if mime_types.size == 0
        mime_type = ''
    else
        mime_type = mime_types[0].to_s
    end
    attachment = WPDB::Post.create(
        :comment_count         => 0,
        :comment_status        => 'open',
        :guid                  => url,
        :menu_order            => 0,
        :ping_status           => 'open',
        :pinged                => '',
        :author                => author,
        :post_content          => '',
        :post_content_filtered => '',
        :post_excerpt          => '',
        :post_mime_type        => mime_type,
        :post_name             => basename,
        :post_parent           => post_id,
        :post_password         => '',
        :post_status           => 'inherit',
        :post_title            => basename,
        :post_type             => 'attachment',
        :to_ping               => '',
    )
    WPDB::PostMeta.create(
        :meta_key   => '_wp_attached_file',
        :meta_value => img_meta[:file],
        :post_id    => attachment.ID,
    )
    WPDB::PostMeta.create(
        :meta_key   => '_wp_attachment_metadata',
        :meta_value => PHP.serialize(img_meta),
        :post_id    => attachment.ID,
    )
    WPDB::PostMeta.create(
        :meta_key   => '_thumbnail_id',
        :meta_value => attachment.ID,
        :post_id    => post_id,
    )
  end

  def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
  end

  def login_editor
    click_link('Log in')
    fill_in 'Username', with: 'editor'
    fill_in 'Password', with: 'editor'
    click_button 'Log In'
  end

  # Timings:
  # - 0.552375
  # - 0.516728
  # - 0.551325
  # mean = 0.540142667
  #
  # (From run on Feb 25):
  # - 1.22162
  # - 1.3177
  # - 1.288371
  #
  # (March 4)
  # - 0.443992
  # - 0.456232
  # - 0.50325
  #
  # (direct inserting, March 4)
  # - 0.036109
  # - 0.0266
  # - 0.03718

  # NB: When you use this, make sure you access the page again *after* creating
  # the game, before testing the expectation. Otherwise, you'll be looking at
  # an old page.
  def make_game(user_login: 'admin')
    post_title   = FFaker::Lorem.words(rand(2..8)).join(' ')
    post_content = FFaker::Lorem.paragraphs(rand(3..10)).join('<p>')
    author       = WPDB::User.first(:user_login => user_login)
    WPDB::Post.create(
      :post_title            => post_title,
      :post_content          => post_content,
      :author                => author,
      :post_excerpt          => '',
      :to_ping               => '',
      :pinged                => '',
      :post_content_filtered => '',
      :post_status           => 'publish',
      :post_type             => 'ivanhoe_game',
      :comment_status        => 'closed',
    )
  end
  # TODO: We need some way to optionally visit the page after creating the
  # object.

  # Timings:
  # - 2.253767
  # - 2.242212
  # - 2.290473
  # [2.253767, 2.242212, 2.290473]
  # mean: 2.262150667
  #
  # After:
  # - 1.069471
  # - 1.052842
  # - 1.049032
  # [1.069471, 1.052842, 1.049032]
  # mean: 1.057115
  def make_role(user_login: 'admin', game_id: nil)
    if game_id.nil?
      game_id = WPDB::Post.first(:post_type => 'ivanhoe_game').ID
    elsif game_id.respond_to? :ID
      game_id = game_id.ID
    end
    post_title   = FFaker::Lorem.words(rand(2..4)).join(' ')
    post_content = FFaker::Lorem.paragraphs(rand(3..10)).join('<p>')

    author       = WPDB::User.first(:user_login => user_login)
    role = WPDB::Post.create(
      :post_title            => post_title,
      :author                => author,
      :post_content          => post_content,
      :post_parent           => game_id,
      :post_excerpt          => '',
      :to_ping               => '',
      :pinged                => '',
      :post_content_filtered => '',
      :post_status           => 'publish',
      :post_type             => 'ivanhoe_role',
      :comment_status        => 'closed',
    )
    post_thumbnail(author, 'spec/dumps/puppy.jpg', role.ID)

    sleep 1
    role
  end

  def make_a_move
    click_button 'Make a Move'
    fill_in 'post_title', :with => FFaker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

# This respond to move function primarily covers the single-ivanhoe_move view
  def respond_to_move
    click_link('Respond')
    fill_in 'post_title', :with => FFaker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

# This function covers responding to a move from the single-ivanhoe_game page.
  def main_page_respond_to_move
    first('.new_source').click
    click_button('Respond')
    fill_in 'post_title', :with => FFaker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

# This function covers responding to multiple moves from the single-ivanhoe_game page
  def respond_to_multiple_moves
    moves = page.all('#moves article')

    @num_sources = rand(2...@num_moves)
    moves[0...@num_sources].each do |move|
      within move do
        find('.new_source').click
      end
    end
    click_button 'Respond'
    fill_in 'post_title', :with => FFaker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

end
