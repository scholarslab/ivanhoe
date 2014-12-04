require 'spec_helper'

def tiny_mce_fill_in(name, args)
  page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
end

def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
end

def make_game
    click_link 'Make a Game'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def make_a_move
    click_link 'Make a move'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

def respond_to_move
    click_link('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
end

describe 'Make a Game View', :type => :feature, :js => true do

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        click_link('Make a Game')
    end

    it 'has the WP Editor' do
      expect(page).to have_selector('.wp-editor-container')
    end

    it 'has the Make a Game header' do
      expect(page).to have_content('Make a Game')
    end

    it 'has a game title label' do
      expect(page).to have_content('Game Title')
    end

    it 'has a required game title field' do
      expect(page).to have_field('post_title', :type => 'text')
      click_button 'Save'
      expect(page).to have_content('A title is required')
    end

    it 'has a game thumbnail label' do
      expect(page).to have_content('Game Thumbnail')
    end

    it 'has a game thumbnail input' do
      expect(page).to have_field('post_thumbnail', :type => 'file')
    end

    it 'has a game description label' do
      expect(page).to have_content('Game Description')
    end

    it 'has the Add Media button' do
      expect(page).to have_selector('#insert-media-button')
    end

    it 'has a required game description input' do
      expect(page).to have_selector('#wp-post_content-wrap')
      # not sure if this is the best selector for this
      click_button 'Save'
      expect(page).to have_content('A description is required')
    end

    it 'has a save button' do
      expect(page).to have_selector('input[type=submit][value=Save]')
    end

end
