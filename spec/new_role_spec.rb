require 'spec_helper'

describe 'Make a Role View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        click_link('Games')
        login
        make_game
        first('.game-title a').click
        click_link('Make a Role!')
    end

    it 'has the Make a Role header' do
      expect(page).to have_content('MAKE A ROLE')
    end

    it 'has a role name label' do
      expect(page).to have_content('Role Name')
    end

    it 'has a required role name field' do
      expect(page).to have_field('post_title', :type => 'text')
      click_button 'Save'
      expect(page).to have_content('A title is required')
    end

    it 'has a role thumbnail label' do
      expect(page).to have_content('Role Thumbnail')
    end

    it 'has a role thumbnail input' do
      expect(page).to have_field('post_thumbnail', :type => 'file')
    end

    it 'has a role description label' do
      expect(page).to have_content('Role Description')
    end

    it 'has the Add Media button' do
      expect(page).to have_selector('#insert-media-button')
    end

    it 'has a required role description input' do
      expect(page).to have_selector('#wp-post_content-wrap')
      # not sure if this is the best selector for this
      click_button 'Save'
      expect(page).to have_content('A description is required')
    end

    it 'has a save button' do
      expect(page).to have_selector('input[type=submit][value=Save]')
    end

    it 'indicates the game with which the role is connected' do
      expect(page).to have_selector('.new-ivanhoe-meta')
    end

    it 'has a link to the game with which the role is connected' do
      within('.new-ivanhoe-meta') do
        expect(page).to have_selector('a')
      end
    end

    describe 'with role thumbnail' do

      before do

        attach_file('post_thumbnail', 'spec/dumps/puppy.jpg')
        fill_in 'post_title', :with => FFaker::Lorem.words(rand(2..4)).join(' ')
        tiny_mce_fill_in('post_content', :with => FFaker::Lorem.paragraphs(rand(3..10)).join('<p>'))
        click_button 'Save'

      end

      it 'has a role picture' do
        within('#game-data .role') do
          expect(page).to have_selector('img')
        end
      end

    end

end
